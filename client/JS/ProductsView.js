class ProductsView {

    constructor() {
        this.$categoryTable = $('#category-table');
        this.$productTable = $('#product-table');
        this.listCategories();

    }


    listCategories() {

        let self = this;

        $.ajax({
            url: 'http://localhost/SPITZER_FE_Uebung5/index.php?action=listTypes',
            method: 'GET',
            success: function (response) {
                self.clearCategoryTable();
                self.clearProductTable();
                self.fillCategoryTable(response);
            },
            error: function (error) {
                self.errorOutput(error);
            }
        });

    }

    clearCategoryTable() {
        this.$categoryTable.find("tbody").empty();
        this.$categoryTable.find("thead").empty();
    }

    clearProductTable() {
        this.$productTable.find("tbody").empty();
        this.$productTable.find("thead").empty();
    }

    fillCategoryTable(data) {

        for (let i = 0; i < data.length; i++) {
            let $type = data[i].productType;
            let $url = data[i].url;
            this.fillCategoryRow($type, $url);
        }
    }

    fillCategoryRow(cat, url) {

        let self = this;

        let $row = $('<tr></tr>');
        let category = ('<td>' + cat + '</td>');

        $row.append(category);

        $row.on('click', function () {
            self.listProducts(url);
            self.activeTableRow($row);
        });

        this.$categoryTable.find("tbody").append($row);

    }

    listProducts(url) {
        let self = this;

        $.ajax({
            url: url,
            method: 'GET',
            success: function (response) {
                self.clearProductTable();
                self.catchEmptyCategories(response['productType']);
                self.fillProductTable(response['products']);
            },
            error: function (error) {
                self.errorOutput(error);
            }
        });
    }

    activeTableRow($row) {
        $row.addClass("selected").siblings().removeClass("selected");
    }

    fillProductTable(products) {

        for (let item in products) {
            this.fillProductRow(products[item]['name'], products[item]['productId']);
        }
    }

    fillProductRow(name, id) {

        let self = this;

        let $row = $('<tr></tr>');
        let $name = $("<td>" + name + "</td>");

        $name.addClass("class='col-sm-3'");
        $row.append($name);

        let $addButton = $("<td><i class='fas fa-cart-arrow-down'></i></td>");
        $addButton.addClass('add-button text-right');

        $addButton.on('click', function () {
            self.addToCart(id);
        });

        $row.append($addButton);

        this.$productTable.find("tbody").append($row);

    }

    catchEmptyCategories(type) {

        if (!type) {
            let $row = $('<tr></tr>');
            let error = ("<th>Sorry, no products so far …</th>");
            $row.append(error);
            this.$productTable.find("thead").append($row);
        }
    }

    errorOutput(error) {
        console.log(error);
    }

    addToCart(id) {

        let self = this;

        $.ajax({
            url: "http://localhost/SPITZER_FE_Uebung5/index.php?action=addArticle&articleId=" + id,
            method: 'GET',
            success: function (response) {
                self.alertStatus(response);
            },
            error: function (error) {
                self.errorOutput(error);
            }
        });
    }

    alertStatus(response) {

        let self = this;

        if (response['state'] === "OK") {
            let state = 'Item added to cart';
            self.createModal(state);
        } else {
            let state = 'Oops, something went wrong';
            self.createModal(state);
        }

        $('#state-modal').modal('toggle');
    }

    createModal(state) {

        let modal = ('<div id ="state-modal" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-sm"><div id = "modal-text" class="modal-content">' + state +
            '</div></div></div>');
        this.$productTable.append(modal);
    }


}

