<style>
.btn.rounded-circle {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 2px;
    position: relative;
    border-radius: 50% !important;
    transition: all 0.3s ease;
}

.btn.rounded-circle:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.btn.rounded-circle i {
    margin: 0;
    font-size: 14px;
}

/* Instant CSS Tooltips */
[data-tooltip] {
    position: relative;
}

[data-tooltip]:before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 4px 8px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    visibility: hidden;
    z-index: 1000;
    opacity: 0;
    transition: opacity 0.3s ease;
}

[data-tooltip]:hover:before {
    visibility: visible;
    opacity: 1;
}

/* Button colors */
.btn-info.rounded-circle {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
}

.btn-success.rounded-circle {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.btn-danger.rounded-circle {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn-warning.rounded-circle {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}
</style> 