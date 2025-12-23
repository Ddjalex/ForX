<div class="notification-container" style="position: relative;">
    <button type="button" class="notification-bell" id="notification-bell" style="
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        position: relative;
        color: #00D4AA;
        padding: 8px;
        transition: all 0.3s ease;
    ">
        🔔
        <span class="notification-badge" id="notification-badge" style="
            position: absolute;
            top: 0;
            right: 0;
            background: #FF4757;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            display: none;
        ">0</span>
    </button>

    <div class="notification-panel" id="notification-panel" style="
        position: absolute;
        top: 40px;
        right: 0;
        width: 350px;
        background: #0a1628;
        border: 1px solid #00D4AA;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 212, 170, 0.2);
        z-index: 1000;
        display: none;
        flex-direction: column;
        max-height: 500px;
        overflow: hidden;
    ">
        <div style="
            padding: 12px 16px;
            background: #0f2438;
            border-bottom: 1px solid #00D4AA;
            display: flex;
            justify-content: space-between;
            align-items: center;
        ">
            <h4 style="margin: 0; color: #00D4AA; font-size: 1rem;">Notifications</h4>
            <button type="button" id="close-notifications" style="
                background: none;
                border: none;
                color: #00D4AA;
                font-size: 1.2rem;
                cursor: pointer;
            ">×</button>
        </div>

        <div id="notification-list" style="
            flex: 1;
            overflow-y: auto;
            max-height: 400px;
        ">
            <div style="
                padding: 20px;
                text-align: center;
                color: #888;
            ">
                Loading notifications...
            </div>
        </div>

        <div style="
            padding: 12px 16px;
            background: #0f2438;
            border-top: 1px solid #00D4AA;
            display: flex;
            gap: 8px;
        ">
            <button type="button" id="clear-notifications" style="
                flex: 1;
                padding: 8px;
                background: #00D4AA;
                color: #0a1628;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: bold;
                font-size: 0.85rem;
            ">Mark All as Read</button>
            <button type="button" id="delete-all-notifications" style="
                flex: 1;
                padding: 8px;
                background: #FF4757;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: bold;
                font-size: 0.85rem;
            ">Delete All</button>
        </div>
    </div>
</div>

<style>
.notification-bell:hover {
    transform: scale(1.1);
    color: #1affff !important;
}

.notification-panel::-webkit-scrollbar {
    width: 6px;
}

.notification-panel::-webkit-scrollbar-track {
    background: #0a1628;
}

.notification-panel::-webkit-scrollbar-thumb {
    background: #00D4AA;
    border-radius: 3px;
}

.notification-item {
    padding: 12px 16px;
    border-bottom: 1px solid #1a3a52;
    cursor: pointer;
    transition: all 0.3s ease;
}

.notification-item:hover {
    background: #0f2438;
}

.notification-item.unread {
    background: #0f2438;
    border-left: 3px solid #00D4AA;
}

.notification-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.notification-item-title {
    color: #00D4AA;
    font-weight: bold;
    font-size: 0.9rem;
    margin: 0;
}

.notification-item-time {
    color: #666;
    font-size: 0.75rem;
}

.notification-item-message {
    color: #ccc;
    font-size: 0.85rem;
    margin: 0;
    line-height: 1.4;
}
</style>
