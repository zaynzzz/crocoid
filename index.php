<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tools Cronosengine</title>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="./icon.png">
    <style>
        .selected {
            border: 2px solid #007bff;
        }
        .payment-method div {
            cursor: pointer;
            padding: 10px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Pilih Metode Pembayaran</h2>
    <div class="payment-method">
        <div id="qris-option" onclick="selectMethod('qris')">
            <img src="https://img.icons8.com/color/48/000000/qr-code.png" alt="QRIS" />
            QRIS
        </div>
        <div id="wallet-option" onclick="selectMethod('wallet')">
            <img src="https://img.icons8.com/color/48/000000/wallet.png" alt="E-Wallet" />
            E-Wallet
        </div>
        <div id="va-option" onclick="selectMethod('va')">
            <img src="https://img.icons8.com/color/48/000000/bank.png" alt="Virtual Account" />
            Virtual Account
        </div>
       <div id="task-option" class="task-manager" onclick="window.location.href='tasking.php'">
            <a href="tasking.php" class="task-link">
                <img src="https://img.icons8.com/ios-filled/50/228BE6/task-completed.png" alt="Task Manager Icon" />
                <span>Task Manager</span>
            </a>
        </div>
       <div id="task-option" class="task-manager" onclick="window.location.href='tasking.php'">
            <a href="coretan.php" class="task-link">
                <img src="https://img.icons8.com/ios-filled/50/228BE6/anonymous-mask.png" alt="Task Manager Icon" />
                <span>  Quotes Harian</span>
            </a>
        </div>
       <div id="task-option" class="task-manager" onclick="window.location.href='tasking.php'">
            <a href="rc.php" class="task-link">
                <img src="https://img.icons8.com/ios-filled/50/228BE6/circular-arrows--v1.png" alt="Task Manager Icon" />
                <span>Resend Kolbek</span>
            </a>
        </div>
    </div>

    <div class="credential-selection" id="credential-selection">
        <label for="project-select">Pilih Proyek:</label>
        <select id="project-select" style="width: 100%;">
            <option value="">Loading...</option>
        </select>
    </div>

    <div class="input-amount" id="amount-input">
        <label for="amount">Nominal (Amount):</label>
        <input type="number" id="amount" name="amount" placeholder="Masukkan nominal">
    </div>
    <div class="input-phone" id="phone-input" style="display:none">
        <label for="phoneNumber">Nomor Telepon:</label>
        <input type="text" id="phoneNumber" name="phoneNumber" placeholder="Masukkan nomor telepon">
    </div>
    <div class="wallet-channel-selection" id="wallet-channel-selection" style="display: none;">
        <label for="wallet-channel">Pilih Channel E-Wallet:</label>
        <select id="wallet-channel" name="channel" style="width: 100%;">
            <option value="">Pilih E-Wallet</option>
            <option value="ovo">Ovo</option>
            <option value="dana">Dana</option>
            <option value="shopeepay">ShopeePay</option>
            <option value="linkaja">LinkAja</option>
        </select>
    </div>

    <div class="va-channel-selection" id="va-channel-selection" style="display: none;">
        <label for="va-channel">Pilih Bank untuk Virtual Account:</label>
        <select id="va-channel" style="width: 100%;">
            <option value="">Pilih Bank</option>
            <option value="008">Mandiri</option>
            <option value="014">BCA</option>
            <option value="002">BRI</option>
            <option value="009">BNI</option>
            <option value="013">Permata</option>
            <option value="011">Danamon</option>
            <option value="022">CIMB</option>
            <option value="153">Sahabat Sampoerna</option>
        </select>
    </div>

    <button class="generate-btn" id="generate-btn" onclick="generatePayment()">Generate</button>

    <div id="loading" style="display: none;">
        <div class="spinner"></div>
        <p>Loading...</p>
    </div>

    <div id="response"></div>
</div>

<div class="popup" id="popup">
    <div id="popup-content"></div>
    <button onclick="closePopup()">Close</button>
</div>

<div class="overlay" id="overlay"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    let selectedMethod = null;

    function loadProjects() {
        fetch('get_projects.php')
            .then(response => response.json())
            .then(data => {
                let projectSelect = document.getElementById('project-select');
                projectSelect.innerHTML = '<option value="">Pilih Proyek</option>';
                data.forEach(project => {
                    let option = document.createElement('option');
                    option.value = project;
                    option.textContent = project;
                    projectSelect.appendChild(option);
                });
                $('#project-select').select2({ placeholder: "Pilih Proyek", allowClear: true });
            })
            .catch(error => {
                console.error('Error fetching projects:', error);
                document.getElementById('project-select').innerHTML = '<option value="">Error loading projects</option>';
            });
    }

    window.onload = loadProjects;

    function selectMethod(method) {
        document.getElementById('qris-option').classList.remove('selected');
        document.getElementById('wallet-option').classList.remove('selected');
        document.getElementById('va-option').classList.remove('selected');
        
        document.getElementById('amount-input').style.display = 'none';
        document.getElementById('phone-input').style.display = 'none';
        document.getElementById('va-channel-selection').style.display = 'none';
        document.getElementById('wallet-channel-selection').style.display = 'none';

        if (method === 'qris') {
            document.getElementById('qris-option').classList.add('selected');
            selectedMethod = 'qris';
            document.getElementById('amount-input').style.display = 'block';
        } else if (method === 'wallet') {
            document.getElementById('wallet-option').classList.add('selected');
            selectedMethod = 'wallet';
            document.getElementById('amount-input').style.display = 'block';
            document.getElementById('phone-input').style.display = 'block';
            document.getElementById('wallet-channel-selection').style.display = 'block';
        } else if (method === 'va') {
            document.getElementById('va-option').classList.add('selected');
            selectedMethod = 'va';
            document.getElementById('amount-input').style.display = 'block';
            document.getElementById('va-channel-selection').style.display = 'block';
        }

        document.getElementById('credential-selection').style.display = 'block';
        document.getElementById('generate-btn').style.display = 'inline-block';
        document.getElementById('response').innerHTML = '';
        document.getElementById('popup-content').innerHTML = '';
    }

    function generatePayment() {
        let amount = document.getElementById('amount').value;
        let projectName = $('#project-select').val();
        let channel = document.getElementById('wallet-channel').value;
        let phoneNumber = document.getElementById('phoneNumber').value;
        let vaChannel = document.getElementById('va-channel').value;

        if (!amount || amount <= 0) {
            alert('Masukkan nominal yang valid!');
            return;
        }
        if (!projectName) {
            alert('Pilih proyek terlebih dahulu!');
            return;
        }
        if (selectedMethod === 'wallet' && !phoneNumber) {
            alert('Masukkan nomor telepon!');
            return;
        }
        if (selectedMethod === 'va' && !vaChannel) {
            alert('Pilih bank untuk Virtual Account!');
            return;
        }

        let formData = new FormData();
        formData.append('amount', amount);
        formData.append('projectName', projectName);
        if (selectedMethod === 'wallet') {
            formData.append('channel', channel);
            formData.append('phoneNumber', phoneNumber);
        } else if (selectedMethod === 'va') {
            formData.append('channel', vaChannel);
        }

        document.getElementById('loading').style.display = 'block';

        fetch('process_qris.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('response').innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loading').style.display = 'none';
            document.getElementById('response').innerHTML = '<p style="color: red;">Terjadi kesalahan. Silakan coba lagi.</p>';
        });
    }

    function closePopup() {
        document.getElementById('popup-content').innerHTML = '';
        document.getElementById('popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }
</script>

</body>
</html>