function convertNumberToWords(number) {
    const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
    const teens = ['', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen',
        'Nineteen'
    ];
    const tens = ['', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

    function convertGroup(number) {
        if (number === 0) return '';

        let output = '';

        if (number >= 100) {
            output += ones[Math.floor(number / 100)] + ' Hundred ';
            number %= 100;
        }

        if (number >= 20) {
            output += tens[Math.floor(number / 10)] + ' ';
            number %= 10;
        }

        if (number > 0) {
            if (number < 10) {
                output += ones[number] + ' ';
            } else {
                output += teens[number - 10] + ' ';
            }
        }

        return output;
    }

    if (number === 0) {
        return 'Zero';
    }

    let result = '';
    let billion = Math.floor(number / 1000000000);
    let million = Math.floor((number % 1000000000) / 1000000);
    let thousand = Math.floor((number % 1000000) / 1000);
    let remainder = number % 1000;

    if (billion > 0) {
        result += convertGroup(billion) + 'Billion ';
    }

    if (million > 0) {
        result += convertGroup(million) + 'Million ';
    }

    if (thousand > 0) {
        result += convertGroup(thousand) + 'Thousand ';
    }

    result += convertGroup(remainder);

    return result.trim();
}

// Example usage:
const numericValue = $OrdersTotalPrice;
const wordRepresentation = convertNumberToWords(numericValue);
document.getElementById('word_payment').innerHTML = (
    wordRepresentation +
    " only ");