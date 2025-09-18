import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('chartjs:connect', this._onConnect.bind(this));
    }

    disconnect() {
        this.element.removeEventListener('chartjs:connect', this._onConnect.bind(this));
    }

    _onConnect(event) {
        const chart = event.detail.chart;
        const isPct = chart.options.scales.y.max === 100;

        if (!chart.options.plugins.tooltip.callbacks) {
            chart.options.plugins.tooltip.callbacks = {};
        }

        chart.options.plugins.tooltip.callbacks.footer = (tooltipItems) => {
            let sum = 0;
            tooltipItems.forEach(function(tooltipItem) {
                sum += tooltipItem.parsed.y;
            });
            
            if (isPct) {
                return 'Total: ' + sum.toFixed(1) + '%';
            }
            return 'Total: ' + sum;
        };
        
        chart.update();
    }
}
