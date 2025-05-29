import './bootstrap';
import Chart from 'chart.js/auto';
import $ from 'jquery';

$(document).ready(function () {
    if (document.getElementById('completionRateChart')) {
        new Chart(document.getElementById('completionRateChart'), {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'Cancelled'],
                datasets: [{
                    data: window.completionStats,
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                }]
            }
        });
    }

    if (document.getElementById('cleanerRatingChart')) {
        new Chart(document.getElementById('cleanerRatingChart'), {
            type: 'bar',
            data: {
                labels: window.cleanerNames,
                datasets: [{
                    label: 'Average Rating',
                    data: window.cleanerRatings,
                    backgroundColor: 'rgba(255, 193, 7, 0.7)'
                }]
            }
        });
    }
});
