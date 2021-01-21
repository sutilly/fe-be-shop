class ShoppingCartView {

    constructor() {
        this.$cartTable = $('#cart-table');
        this.$totalCharge = $('#total-charge');
        this.loadCartContent();
    }

    loadCartContent() {

        let self = this;

        $.ajax({
                url: "http://localhost/SPITZER_FE_Uebung5/index.php?action=listCart",
                method: 'GET',
                success: function (cart) {
                    self.routeCartView(cart);
                },
                error: function (error) {
                    self.errorOutput(error);
                }
            }
        )
    }

    routeCartView(cart) {

        this.clearCartTable();

        let self = this;

        if (cart) {
            self.fillCartTable(cart);
        } else {
            self.showEmptyCartNote();
        }

        self.displayTotal();

    }


    clearCartTable() {
        this.$cartTable.find('tbody').empty();
    }

    fillCartTable(cart) {

        for (let i in cart) {

            let self = this;

            let item = cart[i];
            let $tr = $('<tr></tr>');

            let itemId = item['Id'];
            let itemName = item ['articleName'];
            let itemPrice = parseFloat(item['price']).toFixed(2);
            let itemAmount = item ['amount'];
            let itemTotal = (itemPrice * itemAmount).toFixed(2);

            $tr.append('<td>' + itemId + '</td>');
            $tr.append('<td>' + itemName + '</td>');
            $tr.append($('<td>' + itemAmount + '</td>').addClass('text-center'));
            $tr.append($('<td>' + itemPrice + ' €</td>').addClass('text-right'));
            $tr.append($('<td>' + itemTotal + ' €</td>').addClass(('text-right item-total')));

            let $plusButton = self.createPlusButton(itemId);
            $tr.append($plusButton);

            let $minusButton = self.createMinusButton(itemId);
            $tr.append($minusButton);

            self.$cartTable.find('tbody').append($tr);
        }
    }


    createPlusButton(itemId) {

        let self = this;
        let $plus = $('<td><i class = "change-quantity fas fa-plus-circle"></i></td>');

        $plus.addClass('text-right');

        $plus.on('click', function () {
            self.increaseQuantity(itemId);
        });

        return $plus;

    }

    createMinusButton(itemId) {

        let self = this;
        let $minus = $('<td><i class = "change-quantity fas fa-minus-circle"></i></td>');

        $minus.addClass('text-left');

        $minus.on('click', function () {
            self.decreaseQuantity(itemId);
        })

        return $minus;

    }

    increaseQuantity(itemId) {

        let self = this;

        $.ajax({
            url: "http://localhost/SPITZER_FE_Uebung5/index.php?action=addArticle&articleId=" + itemId,
            method: "GET",
            success: function () {
                self.loadCartContent();
            },
            error: function (error) {
                self.errorOutput(error);
            }
        })
    }

    decreaseQuantity(itemId) {

        let self = this;

        $.ajax({
            url: "http://localhost/SPITZER_FE_Uebung5/index.php?action=removeArticle&articleId=" + itemId,
            method: "GET",
            success: function () {
                self.loadCartContent();
            },
            error: function (error) {
                self.errorOutput(error);
            }
        })
    }

    calculateTotal() {

        let totalCost = 0.00;
        let $itemTotalData = this.$cartTable.find('tbody').find('tr .item-total');

        $itemTotalData.each(function () {
            let itemTotal = $(this).text().replace(' €', '');
            let floatItemTotal = (parseFloat(itemTotal + totalCost));
            totalCost += floatItemTotal;
        })

        return totalCost.toFixed(2);
    }


    displayTotal() {

        let totalCharge = 0.00;
        let $subtotalCell = this.$totalCharge.find('#subtotal');

        let totalChargeValue = this.calculateTotal();
        console.log(totalChargeValue);

        if (totalChargeValue > 0) {
            totalCharge = totalChargeValue;
        }

        let priceTotalCharge = parseFloat(totalCharge).toFixed(2) + ' €';
        $subtotalCell.text(priceTotalCharge);
    }

    showEmptyCartNote() {
        let $tr = $('<tr></tr>');
        let $emptyNote = $('<td>Your shopping cart is empty!</td>');

        $emptyNote.attr('colspan', 7);
        $emptyNote.attr('id', 'empty-cart');
        $emptyNote.addClass('text-center');

        $tr.append($emptyNote);

        this.$cartTable.find('tbody').append($tr);
    }

    errorOutput(error) {
        console.log(error);
    }
}



