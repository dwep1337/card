class CardManager {
    constructor() {
        this.currentPage = 1;
        this.totalPages = 1;
        this.limit = 10;
        this.initElements();
        this.bindEvents();
        this.loadCards(this.currentPage);
    }

    initElements() {
        this.cardsContainer = $('#cardsContainer');
        this.prevPageBtn = $('#prevPage');
        this.nextPageBtn = $('#nextPage');
        this.pageInfo = $('#pageInfo');
        this.totalCardsElement = $('#totalCards');
        this.logoutBtn = $('#logoutBtn');
    }

    bindEvents() {
        this.logoutBtn.click(() => this.handleLogout());
        this.prevPageBtn.click(() => this.prevPage());
        this.nextPageBtn.click(() => this.nextPage());
    }

    async handleLogout() {
        if (!confirm('Deseja realmente sair?')) return;

        try {
            await $.ajax({
                url: '/logout',
                type: 'POST'
            });
        } catch (error) {
            console.error('Erro ao fazer logout:', error.responseText);
        } finally {
            window.location.href = '/login';
        }
    }

    // Pagination methods
    prevPage() {
        if (this.currentPage > 1) {
            this.currentPage--;
            this.loadCards(this.currentPage);
        }
    }

    nextPage() {
        if (this.currentPage < this.totalPages) {
            this.currentPage++;
            this.loadCards(this.currentPage);
        }
    }

    updatePagination(data) {
        this.currentPage = data.currentPage;
        this.totalPages = data.totalPages;

        this.pageInfo.text(`Página ${this.currentPage} de ${this.totalPages}`);
        this.prevPageBtn.prop('disabled', this.currentPage <= 1);
        this.nextPageBtn.prop('disabled', this.currentPage >= this.totalPages);
    }

    // Card methods
    async loadCards(page) {
        this.showLoading();

        try {
            const response = await $.ajax({
                url: `/cards?page=${page}&limit=${this.limit}`,
                type: 'GET'
            });

            this.renderCards(response.cards);
            this.updatePagination(response);
            this.totalCardsElement.text(response.totalCards);
        } catch (error) {
            this.handleLoadError(error);
        } finally {
            this.hideLoading();
        }
    }

    handleLoadError(xhr) {
        console.error('Erro ao carregar cartões:', xhr.responseText);

        if (xhr.status === 401) {
            alert('Sessão expirada. Por favor, faça login novamente.');
            window.location.href = '/login';
        } else {
            this.cardsContainer.html(`
                <div class="alert alert-danger">
                    Erro ao carregar cartões. Tente novamente mais tarde.
                </div>
            `);
        }
    }

    renderCards(cards) {
        if (cards.length === 0) {
            this.cardsContainer.html('<p class="no-cards">Nenhum cartão encontrado</p>');
            return;
        }

        const html = cards.map(card => this.createCardHtml(card)).join('');
        this.cardsContainer.html(html);

        this.bindCardActions();
    }

    createCardHtml(card) {
        return `
        <div class="card-item" data-id="${card.id}">
            <div class="card-info">
                <h4>${card.numero}</h4>
                <p>Titular: ${card.nome} | Vencimento: ${card.validade} | cvv ${card.cvv}</p>
                <small>Cadastrado em: ${this.formatDate(card.created_at)}</small>
            </div>
            <div class="card-actions">
                <button class="btn-action btn-edit" title="Editar" data-id="${card.id}">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn-action btn-delete" title="Excluir" data-id="${card.id}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
        `;
    }

    bindCardActions() {
        $('.btn-edit').click((e) => {
            const cardId = $(e.currentTarget).data('id');
            this.editCard(cardId);
        });

        $('.btn-delete').click(async (e) => {
            const cardId = $(e.currentTarget).data('id');
            await this.deleteCard(cardId);
        });
    }

    async deleteCard(cardId) {
        if (!confirm('Tem certeza que deseja excluir este cartão?')) return;

        try {
            await $.ajax({
                url: `/cards/${cardId}`,
                type: 'DELETE',
            });
            await this.loadCards(this.currentPage);
        } catch (error) {
            alert('Erro ao excluir cartão: ' + error.responseText);
        }
    }

    editCard(cardId) {
        console.log('Editar cartão:', cardId);
    }

    // Utility methods
    showLoading() {
        this.cardsContainer.html(`
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>
        `);
    }

    hideLoading() {
        this.cardsContainer.find('.loading-spinner').remove();
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR') + ' ' + date.toLocaleTimeString('pt-BR');
    }
}

$(document).ready(() => {
    new CardManager();
});