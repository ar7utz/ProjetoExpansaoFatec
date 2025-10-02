<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/output.css">
    <title>LOGIN ADM</title>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <form action="./autenticação/logon.php" method="POST" class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-800">LOGIN</h1>
        </div>
        <div class="mb-4">
            <input type="text" placeholder="Usuário:" name="usuario" autocomplete="off" required
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-2">
            <input type="password" placeholder="Senha:" name="senha" required
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <input type="submit" value="Login"
                class="w-full px-4 py-2 bg-tollens text-black font-semibold rounded hover:bg-vermelho-fatec transition duration-300 cursor-pointer">
        </div>
    </form>
</body>
</html>