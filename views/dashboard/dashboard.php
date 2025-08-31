<?php include 'includes/header.php'; ?>

<div class="dashboard-container">
    <!-- Cabeçalho -->
    <div class="dashboard-header">
        <h1 class="page-title">Dashboard</h1>
        <button class="btn-logout" id="logoutBtn">
            <i class="bi bi-box-arrow-right"></i>
            Sair
        </button>
    </div>

    <!-- Total de Cartões -->
    <div class="summary-card">
        <p class="summary-title">Total de Cartões Registrados</p>
        <p class="summary-value" id="totalCards">0</p>
    </div>

    <!-- Lista de Cartões -->
    <div class="cards-list">
        <div class="cards-list-header">
            <h2 class="section-title">Cartões Cadastrados</h2>
            <div class="pagination-controls">
                <button id="prevPage" class="btn-pagination" disabled>
                    <i class="bi bi-chevron-left"></i>
                </button>
                <span id="pageInfo">Página 1 de 1</span>
                <button id="nextPage" class="btn-pagination" disabled>
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Container dinâmico para os cartões -->
        <div id="cardsContainer">
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
