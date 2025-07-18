<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Erro no Sistema - NPR</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f6f6f6; padding: 20px;">

    <table width="100%" style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 6px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <tr>
            <td style="padding: 20px; text-align: center; background-color: #2f855a; color: white; border-top-left-radius: 6px; border-top-right-radius: 6px;">
                <h1 style="margin: 0;">Erro Crítico no Sistema</h1>
            </td>
        </tr>

        <tr>
            <td style="padding: 20px;">
                <p style="font-size: 16px; color: #333;">
                    Um erro inesperado ocorreu no sistema <strong>Natureza Prioridade Renovada (NPR)</strong>.
                </p>

                <p style="font-size: 14px; background-color: #f2f2f2; padding: 10px; border-left: 4px solid #e53e3e; color: #555;">
                    <strong>Mensagem do erro:</strong><br>
                    {{ $exception_message }}
                </p>

                <p style="font-size: 14px; color: #666;">
                    Recomendamos que a equipe técnica analise esse erro o quanto antes para garantir a estabilidade do sistema.
                </p>
            </td>
        </tr>

        <tr>
            <td style="padding: 15px; text-align: center; font-size: 12px; color: #999;">
                © {{ date('Y') }} NPR - Natureza Prioridade Renovada
            </td>
        </tr>
    </table>

</body>
</html>
