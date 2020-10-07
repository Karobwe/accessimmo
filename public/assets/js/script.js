$(function () {
    $('#priceControlRange').on('change', function () {
        let val = this.value
        $('#priceControlRangeValue').val(val + '€')
    })

    $('#priceControlRange').on('input', function () {
        let val = this.value
        $('#priceControlRangeValue').val(val + '€')
    })
})