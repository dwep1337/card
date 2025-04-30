<?php include 'includes/header.php'; ?>

<div class="split-screen">
    <!-- Lado Esquerdo (Preto) -->
    <div class="left-side">
        <div class="content-wrapper">
            <h1 class="display-4 mb-4">Verifique se seu cartão de credito foi vazado na internet</h1>
            <p class="lead">Digite os dados do seu cartão de credito/debito para verificar possíveis vazamentos.</p>
            <ul class="features-list">
                <li><i class="bi bi-shield-check"></i> Verificação instantânea</li>
                <li><i class="bi bi-lock"></i> Dados protegidos, não salvamos suas informações</li>
                <li><i class="bi bi-database"></i> Base de dados atualizada diariamente</li>
            </ul>
        </div>
    </div>

    <!-- Lado Direito (Branco) -->
    <div class="right-side">
        <div class="card-container">
            <!-- Cartão 3D Interativo -->
            <div class="credit-card-wrapper">
                <div class="credit-card">
                    <!-- Frente do Cartão -->
                    <div class="credit-card-front">
                        <div class="credit-card-logo">SecureCard</div>

                        <div class="credit-card-chip">
                            <div class="chip-inner"></div>
                        </div>

                        <div class="number-input">
                            <input type="text" id="numeroCartao" maxlength="19" placeholder="0000 0000 0000 0000"
                                   class="card-input" autocomplete="cc-number">
                        </div>

                        <div class="credit-card-info">
                            <div class="name-input">
                                <label>Titular</label>
                                <input type="text" id="nomeTitular" placeholder="NOME DO TITULAR" class="card-input"
                                       autocomplete="cc-name">
                            </div>

                            <div class="expiry-input">
                                <label>Validade</label>
                                <input type="text" id="validade" maxlength="5" placeholder="MM/AA" class="card-input"
                                       autocomplete="cc-exp">
                            </div>
                        </div>
                    </div>

                    <!-- Verso do Cartão -->
                    <div class="credit-card-back">
                        <div class="credit-card-stripe"></div>
                        <div class="credit-card-cvv">
                            <label>CVV</label>
                            <div class="cvv-input-container">
                                <input type="password" id="cvv" maxlength="3" placeholder="•••" class="card-input"
                                       autocomplete="cc-csc">
                                <button class="btn-toggle-cvv" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botão e Resultado -->
            <div class="verification-section">
                <button id="btnVerificar" class="btn btn-dark btn-lg">
                    <i class="bi bi-shield-lock me-2"></i> Verificar Cartão
                </button>
                <div id="resultado"></div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>