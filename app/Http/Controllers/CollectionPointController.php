<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionPoint\StoreRequest;
use App\Http\Requests\CollectionPoint\UpdateRequest;
use App\Jobs\GetGeoInfoJob;
use App\Models\CollectionPoint;
use App\Services\CollectionPointService;
use App\Services\Operations;
use Database\Seeders\CategoriesTableSeeder;
use Exception;
use GuzzleHttp\Psr7\Query;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CollectionPointController extends Controller
{
    protected CollectionPointService $collectionPointService;

    public function __construct(CollectionPointService $service)
    {
        $this->collectionPointService = $service;
    }

    public function index()
    {
        return CollectionPoint::paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        if ($this->collectionPointService->timeInputIsNotValid($request->open_from, $request->open_to)) {
            return back()
                ->withErrors(['open_to' => 'O horário de fechamento deve ser maior que o de abertura.'])
                ->withInput();
        }

        // OPEN GATE API
        // $geodata = $this->collectionPointService->getGeoInfo($request->input('cep'), $request->input('neighborhood'), $request->input('city'), $request->input('state'), $request->input('street'));



        $days_open = implode(' - ', $request->days_open);

        $point = new CollectionPoint();

        // if ($geodata) {
        //     $point->latitude = $geodata['latitude'];
        //     $point->longitude = $geodata['longitude'];
        // }

        $point->name = $request->input('name');
        $point->cep = str_replace('-', '', $request->input('cep'));
        $point->street = $request->street;
        $point->neighborhood = $request->neighborhood;
        $point->city = $request->city;
        $point->state = $request->state;
        $point->complement = $request->complement;


        $point->user_id = $request->input('user_id');
        $point->open_from = $request->input('open_from');
        $point->open_to = $request->input('open_to');
        $point->days_open = $days_open;
        $point->description = $request->input('description');

        $categories_id = $request->input('categories-id', []);

        try {
            $point->save();
            $point->categories()->sync($categories_id);
            $data = $request->only(['cep', 'neighborhood', 'city', 'state', 'street']);
            GetGeoInfoJob::dispatch($this->collectionPointService, $data, $point->id);

            return redirect()
                ->route('home')
                ->with('success', 'Ponto de coleta cadastrado com sucesso !');
        } catch (QueryException $e) {
            Log::channel('npr')->error('Houve um erro ao salvar o ponto de coleta', ['exception' => $e->getMessage()]);
            if ($e->getCode() === '23000') {
                return back()
                    ->withInput()
                    ->with('error', 'Nome do ponto já cadastrado');
            }
            return back()
                ->withInput()
                ->with('error', 'Houve um erro ao salvar as informações, aguarde alguns instantes e tente novamente');
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $id = Crypt::decrypt($id);
            $point = $this->collectionPointService->findCollectionPointById($id);
            if (Gate::denies('user_can_edit', $point)) {
                abort(403, 'Você não tem permissão para acessar este recurso.');
            }

            if (!$this->collectionPointService->verifyIfUserCreateThePoint($point->user_id, $id)) {
                return back()
                    ->with('error', 'Desculpe, você não tem permissão para alterar estas informações');
            }


            // create model data
            if ($this->collectionPointService->timeInputIsNotValid($request->open_from, $request->open_to)) {
                return back()
                    ->withErrors(['open_to' => 'O horário de fechamento deve ser maior que o de abertura.'])
                    ->withInput();
            }

            $days_open = implode(' - ', $request->days_open);


            $point->name = $request->input('name');
            $point->cep = str_replace('-', '', $request->input('cep'));
            $point->street = $request->street;
            $point->neighborhood = $request->neighborhood;
            $point->city = $request->city;
            $point->state = $request->state;
            $point->complement = $request->complement;

            $point->open_from = $request->input('open_from');
            $point->open_to = $request->input('open_to');
            $point->days_open = $days_open;
            $point->description = $request->input('description');

            $categories_id = $request->input('categories-id', []); // já é um array ou [] por padrão

            $point->updated_at = now();

            $point->save();

            $point->categories()->sync($categories_id);
            // dd($categories_id);


            return redirect()
                ->route('collection_point.view', ['id' => Crypt::encrypt($point->id)])
                ->with('success', 'Informações atualizadas com sucesso');
        } catch (Exception $e) {
            return back()
                ->with('error', 'Desculpe, houve um erro ao atualizar o registro, tente novamente mais tarde.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $id = Operations::decryptId($id);
            $point = $this->collectionPointService->findCollectionPointById($id);
            if (Gate::denies('user_can_delete', $point)) {
                abort(403, "Você não tem permissão para acessar este recurso ");
            }
            $point->delete();
            return redirect()
                ->route('home')
                ->with('success', 'Registro apagado com sucesso');
        } catch (Exception $e) {
            return back()
                ->with('error', 'Erro ao apagar registro, tente novamente mais tarde');
        }
    }
}
