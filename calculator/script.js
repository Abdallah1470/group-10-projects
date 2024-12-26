document.addEventListener('DOMContentLoaded', () => {
    // State management
    let currentOperand = '';
    let previousOperand = '';
    let operation = undefined;
    let memory = 0;
    let history = [];

    // DOM Elements
    const previousOperandElement = document.querySelector('.previous-operand');
    const currentOperandElement = document.querySelector('.current-operand');
    const numberButtons = document.querySelectorAll('[data-number]');
    const operatorButtons = document.querySelectorAll('[data-operator]');
    const functionButtons = document.querySelectorAll('[data-action]');
    const historyPanel = document.querySelector('.history-panel');
    const historyContent = document.querySelector('.history-content');
    const themeSwitch = document.getElementById('theme-switch');

    // Initialize calculator
    init();

    function init() {
        // Remove loading screen after 1 second
        setTimeout(() => {
            document.querySelector('.loading-screen').style.display = 'none';
        }, 1000);

        // Add event listeners
        numberButtons.forEach(button => {
            button.addEventListener('click', () => appendNumber(button.dataset.number));
        });

        operatorButtons.forEach(button => {
            button.addEventListener('click', () => setOperation(button.dataset.operator));
        });

        functionButtons.forEach(button => {
            button.addEventListener('click', () => handleFunction(button.dataset.action));
        });

        // Keyboard support
        document.addEventListener('keydown', handleKeyboard);

        // Theme toggle
        themeSwitch.addEventListener('change', toggleTheme);

        // Initial display update
        updateDisplay();
    }

    function appendNumber(number) {
        if (number === '.' && currentOperand.includes('.')) return;
        currentOperand = currentOperand.toString() + number.toString();
        updateDisplay();
    }

    function setOperation(operator) {
        if (currentOperand === '') return;
        if (previousOperand !== '') {
            calculate();
        }
        operation = operator;
        previousOperand = currentOperand;
        currentOperand = '';
        updateDisplay();
    }

    function calculate() {
        let computation;
        const prev = parseFloat(previousOperand);
        const current = parseFloat(currentOperand);
        
        if (isNaN(prev) || isNaN(current)) return;

        switch (operation) {
            case '+':
                computation = prev + current;
                break;
            case '-':
                computation = prev - current;
                break;
            case '*':
                computation = prev * current;
                break;
            case '/':
                computation = prev / current;
                break;
            default:
                return;
        }

        addToHistory(`${prev} ${operation} ${current} = ${computation}`);
        currentOperand = computation;
        operation = undefined;
        previousOperand = '';
    }

    function handleFunction(action) {
        switch (action) {
            case 'clear':
                currentOperand = '';
                previousOperand = '';
                operation = undefined;
                break;
            case 'calculate':
                calculate();
                break;
            case 'backspace':
                currentOperand = currentOperand.toString().slice(0, -1);
                break;
            case 'sqrt':
                currentOperand = Math.sqrt(parseFloat(currentOperand));
                break;
            case 'power':
                currentOperand = Math.pow(parseFloat(currentOperand), 2);
                break;
            case 'sin':
                currentOperand = Math.sin(parseFloat(currentOperand) * Math.PI / 180);
                break;
            case 'cos':
                currentOperand = Math.cos(parseFloat(currentOperand) * Math.PI / 180);
                break;
            case 'tan':
                currentOperand = Math.tan(parseFloat(currentOperand) * Math.PI / 180);
                break;
            case 'percent':
                currentOperand = parseFloat(currentOperand) / 100;
                break;
            case 'mc':
                memory = 0;
                break;
            case 'mr':
                currentOperand = memory.toString();
                break;
            case 'm+':
                memory += parseFloat(currentOperand) || 0;
                break;
            case 'm-':
                memory -= parseFloat(currentOperand) || 0;
                break;
        }
        updateDisplay();
    }

    function handleKeyboard(e) {
        if (e.key >= '0' && e.key <= '9' || e.key === '.') {
            appendNumber(e.key);
        }
        if (e.key === '+' || e.key === '-' || e.key === '*' || e.key === '/') {
            setOperation(e.key);
        }
        if (e.key === 'Enter' || e.key === '=') {
            e.preventDefault();
            calculate();
        }
        if (e.key === 'Backspace') {
            handleFunction('backspace');
        }
        if (e.key === 'Escape') {
            handleFunction('clear');
        }
    }

    function updateDisplay() {
        currentOperandElement.textContent = formatNumber(currentOperand);
        if (operation != null) {
            previousOperandElement.textContent = `${formatNumber(previousOperand)} ${operation}`;
        } else {
            previousOperandElement.textContent = '';
        }
    }

    function formatNumber(number) {
        const stringNumber = number.toString();
        const integerDigits = parseFloat(stringNumber.split('.')[0]);
        const decimalDigits = stringNumber.split('.')[1];
        let integerDisplay;
        
        if (isNaN(integerDigits)) {
            integerDisplay = '';
        } else {
            integerDisplay = integerDigits.toLocaleString('en', {
                maximumFractionDigits: 0
            });
        }

        if (decimalDigits != null) {
            return `${integerDisplay}.${decimalDigits}`;
        } else {
            return integerDisplay;
        }
    }

    function addToHistory(calculation) {
        history.push(calculation);
        const historyItem = document.createElement('div');
        historyItem.classList.add('history-item');
        historyItem.textContent = calculation;
        historyItem.addEventListener('click', () => {
            currentOperand = calculation.split('=')[1].trim();
            updateDisplay();
        });
        historyContent.appendChild(historyItem);
    }

    function toggleTheme() {
        document.body.setAttribute('data-theme', themeSwitch.checked ? 'dark' : 'light');
    }

    // Toggle history panel
    document.querySelector('.history-header button').addEventListener('click', () => {
        historyContent.innerHTML = '';
        history = [];
    });

    // Show/hide history panel with keyboard shortcut (H key)
    document.addEventListener('keydown', (e) => {
        if (e.key.toLowerCase() === 'h') {
            historyPanel.classList.toggle('show');
        }
    });
});