<?php
include 'includes/header.php';

function saudacaoBrasilia(): string
{
    $hora = new DateTime('now', new DateTimeZone('America/Sao_Paulo'))->format('H');
    return match (true) {
        $hora >= 5 && $hora < 12 => 'Bom dia',
        $hora >= 12 && $hora < 18 => 'Boa tarde',
        default => 'Boa noite',
    };
}
?>

    <div class="split-screen">
        <div class="left-side">
            <div class="content-wrapper">
                <h1 class="display-4"><?php echo saudacaoBrasilia() ?> 😊</h1>
                <p class="lead">Faça login para acessar o painel</p>
            </div>
        </div>
        <div class="right-side">
            <div class="card-container">
                <form id="loginForm">
                    <label for="username">Usuário</label>
                    <input type="text" id="username" name="username" placeholder="Digite seu usuário" required/>

                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" placeholder="Digite sua senha" required/>

                    <div class="btn-container">
                        <button type="submit" id="btnLogin">Entrar</button>
                    </div>
                </form>
                <div id="loginMessage"></div>
            </div>
        </div>
    </div>
<?php include 'includes/footer.php' ?>