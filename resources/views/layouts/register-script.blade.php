<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
<script>
    
    const legalForms = @json($legalForms);

    const availableCountries = ["AT", "BE", "BG", "HR", "CY", "CZ", "DK", "EE", "FI", "FR", "DE", "GR", "HU", "IE", "IT", "LV", "LT", "LU", "MT", "NL", "NO", "PL", "PT", "RO", "SK", "SI", "ES", "SE", "CH", "GB"];
   
    const vatPatterns = {
        AT: {
            pattern: /^ATU[0-9]{8}$/,
            format: "AT + 'U' + 8 digits"
        }, // Austria
        BE: {
            pattern: /^BE[0-1]{1}[0-9]{9}$/,
            format: "BE + 10 digits (starting with 0 or 1)"
        }, // Belgium
        BG: {
            pattern: /^BG[0-9]{9,10}$/,
            format: "BG + 9 or 10 digits"
        }, // Bulgaria
        HR: {
            pattern: /^HR[0-9]{11}$/,
            format: "HR + 11 digits"
        }, // Croatia
        CY: {
            pattern: /^CY[0-9]{8}[A-Z]$/,
            format: "CY + 8 digits + 1 letter"
        }, // Cyprus
        CZ: {
            pattern: /^CZ[0-9]{8,10}$/,
            format: "CZ + 8, 9, or 10 digits"
        }, // Czech Republic
        DK: {
            pattern: /^DK[0-9]{8}$/,
            format: "DK + 8 digits"
        }, // Denmark
        EE: {
            pattern: /^EE[0-9]{9}$/,
            format: "EE + 9 digits"
        }, // Estonia
        FI: {
            pattern: /^FI[0-9]{8}$/,
            format: "FI + 8 digits"
        }, // Finland
        FR: {
            pattern: /^FR[A-Z0-9]{2}[0-9]{9}$/,
            format: "FR + 2 alphanumeric characters + 9 digits"
        }, // France
        DE: {
            pattern: /^DE[0-9]{9}$/,
            format: "DE + 9 digits"
        }, // Germany
        GR: {
            pattern: /^EL[0-9]{9}$/,
            format: "EL + 9 digits"
        }, // Greece
        HU: {
            pattern: /^HU[0-9]{8}$/,
            format: "HU + 8 digits"
        }, // Hungary
        IE: {
            pattern: /^IE[0-9]{7}[A-W](?:[A-I0-9])?$/,
            format: "IE + 7 digits + 1 letter (A-W) + optional 1 digit/letter (A-I or 0-9)"
        }, // Ireland
        IT: {
            pattern: /^IT[0-9]{11}$/,
            format: "IT + 11 digits"
        }, // Italy
        LV: {
            pattern: /^LV[0-9]{11}$/,
            format: "LV + 11 digits"
        }, // Latvia
        LT: {
            pattern: /^LT[0-9]{9,12}$/,
            format: "LT + 9 or 12 digits"
        }, // Lithuania
        LU: {
            pattern: /^LU[0-9]{8}$/,
            format: "LU + 8 digits"
        }, // Luxembourg
        MT: {
            pattern: /^MT[0-9]{8}$/,
            format: "MT + 8 digits"
        }, // Malta
        NL: {
            pattern: /^NL[0-9]{9}B[0-9]{2}$/,
            format: "NL + 9 digits + 'B' + 2 digits"
        }, // Netherlands
        NO: {
            pattern: /^NO[0-9]{9}MVA$/,
            format: "NO + 9 digits + 'MVA'"
        }, // Norway
        PL: {
            pattern: /^PL[0-9]{10}$/,
            format: "PL + 10 digits"
        }, // Poland
        PT: {
            pattern: /^PT[0-9]{9}$/,
            format: "PT + 9 digits"
        }, // Portugal
        RO: {
            pattern: /^RO[0-9]{2,10}$/,
            format: "RO + 2 to 10 digits"
        }, // Romania
        SK: {
            pattern: /^SK[0-9]{10}$/,
            format: "SK + 10 digits"
        }, // Slovakia
        SI: {
            pattern: /^SI[0-9]{8}$/,
            format: "SI + 8 digits"
        }, // Slovenia
        ES: {
            pattern: /^ES[A-Z0-9][0-9]{7}[A-Z0-9]$/,
            format: "ES + 1 letter/digit + 7 digits + 1 letter/digit"
        }, // Spain
        SE: {
            pattern: /^SE[0-9]{12}$/,
            format: "SE + 12 digits"
        }, // Sweden
        CH: {
            pattern: /^CHE[0-9]{9}(MWST|TVA|IVA)?$/,
            format: "CHE + 9 digits + optional 'MWST'/'TVA'/'IVA'"
        }, // Switzerland
        GB: {
            pattern: /^GB[0-9]{9}$/,
            format: "GB + 9 digits"
        }, // United Kingdom
    };
</script>