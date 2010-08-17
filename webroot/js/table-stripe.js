var table_stripe = {
    // 初期化
    constructor: function() {
        // 偶数行の色を設定
        jQuery('table.stripe-table tr:even').css('background-color', '#FFFFFF');
        // 奇数行の色を設定
        jQuery('table.stripe-table tr:odd').css('background-color', '#ecfbe9');
    }
}


$(document).ready(function() {
    table_stripe.constructor();
});