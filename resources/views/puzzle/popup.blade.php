@php
    $popup = false;

    if (request()->is('inicio')) {
        $currentDate = \Carbon\Carbon::now()->toDateTimeString();
        
        $popup = \App\Popup::where('status', 1)
            ->where('begin_at', '<=', $currentDate)
            ->where('finish_at', '>=', $currentDate)
            ->first();
    }
@endphp

@if ($popup)
    <style>
        .modal-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
            animation: fadeInModalPopup 0.5s ease-out;
        }
        @keyframes fadeInModalPopup {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        .modal-popup-content {
            position: relative;
            background: white;
            padding: 0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 90%;
            max-height: 90%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-popup-close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            border: 2px solid black;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .modal-popup-close-btn:hover {
            background: black;
            color: white;
        }

        .modal-popup-image {
            display: block;
            max-width: 100%;
            max-height: 100%;
            margin: auto;
        }
    </style>
    
    <div>
        <div class="modal-popup" id="imageModal">
            <div class="modal-popup-content">
                <button class="modal-popup-close-btn" onclick="closeModal()">×</button>
                <img src="{{ asset('storage/' . $popup->image) }}" class="modal-popup-image">
            </div>
        </div>

        <script>
            window.onload = () => {
                const modal = document.getElementById('imageModal');
                modal.style.display = 'flex';
            };

            function closeModal() {
                const modal = document.getElementById('imageModal');
                modal.style.display = 'none';
            }
        </script>
    </div>
@endif