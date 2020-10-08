$(function () {
    $('#priceControlRange').on('change', function () {
        let val = this.value
        $('#priceControlRangeValue').val(val + '€')
    })

    $('#priceControlRange').on('input', function () {
        let val = this.value
        $('#priceControlRangeValue').val(val + '€')
    })

    makePriceSlider();
    makeSurfaceSlider();
})

function makePriceSlider() {
    let slider = document.getElementById('priceSlider');
    let minPriceValue = document.getElementById('minPriceValue');
    let maxPriceValue = document.getElementById('maxPriceValue');
    let inputs = [minPriceValue, maxPriceValue];

    noUiSlider.create(slider, {
        start: [0, 45000],
        connect: true,
        range: {
            'min': 0,
            'max':
                100000
        }
    });

    slider.noUiSlider.on('update', function (values, handle) {
        inputs[handle].value = values[handle] + '€';
    });
}

function makeSurfaceSlider() {
    let slider = document.getElementById('areaSlider');
    let minPriceValue = document.getElementById('minAreaValue');
    let maxPriceValue = document.getElementById('maxAreaValue');
    let inputs = [minPriceValue, maxPriceValue];

    noUiSlider.create(slider, {
        start: [9, 150],
        connect: true,
        range: {
            'min': 0,
            'max':
                300
        }
    });

    slider.noUiSlider.on('update', function (values, handle) {
        inputs[handle].value = values[handle] + 'm²';
    });
}