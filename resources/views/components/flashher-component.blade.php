<div>
   {{-- Flash Messages Component --}}
@if(session('success'))
    <div class="flash-message flash-success">
        <div class="flash-content">
            <div class="flash-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="flash-text">
                <span class="flash-message-text">{{ session('success') }}</span>
            </div>
            <button onclick="this.closest('.flash-message').remove()" class="flash-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="flash-message flash-error">
        <div class="flash-content">
            <div class="flash-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="flash-text">
                <span class="flash-message-text">{{ session('error') }}</span>
            </div>
            <button onclick="this.closest('.flash-message').remove()" class="flash-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="flash-message flash-warning">
        <div class="flash-content">
            <div class="flash-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="flash-text">
                <span class="flash-message-text">{{ session('warning') }}</span>
            </div>
            <button onclick="this.closest('.flash-message').remove()" class="flash-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

@if(session('info'))
    <div class="flash-message flash-info">
        <div class="flash-content">
            <div class="flash-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="flash-text">
                <span class="flash-message-text">{{ session('info') }}</span>
            </div>
            <button onclick="this.closest('.flash-message').remove()" class="flash-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

<style>
/* Flash Messages Styling */
.flash-message {
    position: relative;
    margin: 16px 20px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    animation: slideInDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(10px);
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.flash-content {
    display: flex;
    align-items: center;
    padding: 16px;
    gap: 12px;
    position: relative;
}

.flash-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    font-size: 20px;
}

.flash-text {
    flex: 1;
    min-width: 0;
}

.flash-message-text {
    display: block;
    font-size: 14px;
    font-weight: 500;
    line-height: 1.5;
    word-wrap: break-word;
}

.flash-close {
    flex-shrink: 0;
    background: transparent;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    font-size: 16px;
}

.flash-close:hover {
    transform: scale(1.1);
}

.flash-close:active {
    transform: scale(0.95);
}

/* Success Style */
.flash-success {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%);
    border-left: 4px solid #10b981;
}

.flash-success .flash-icon {
    background: rgba(16, 185, 129, 0.2);
    color: #059669;
}

.flash-success .flash-message-text {
    color: #065f46;
}

.flash-success .flash-close {
    color: #059669;
}

.flash-success .flash-close:hover {
    background: rgba(16, 185, 129, 0.2);
}

/* Error Style */
.flash-error {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.15) 100%);
    border-left: 4px solid #ef4444;
}

.flash-error .flash-icon {
    background: rgba(239, 68, 68, 0.2);
    color: #dc2626;
}

.flash-error .flash-message-text {
    color: #991b1b;
}

.flash-error .flash-close {
    color: #dc2626;
}

.flash-error .flash-close:hover {
    background: rgba(239, 68, 68, 0.2);
}

/* Warning Style */
.flash-warning {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.15) 0%, rgba(245, 158, 11, 0.15) 100%);
    border-left: 4px solid #fbbf24;
}

.flash-warning .flash-icon {
    background: rgba(251, 191, 36, 0.2);
    color: #f59e0b;
}

.flash-warning .flash-message-text {
    color: #92400e;
}

.flash-warning .flash-close {
    color: #f59e0b;
}

.flash-warning .flash-close:hover {
    background: rgba(251, 191, 36, 0.2);
}

/* Info Style */
.flash-info {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.15) 100%);
    border-left: 4px solid #3b82f6;
}

.flash-info .flash-icon {
    background: rgba(59, 130, 246, 0.2);
    color: #2563eb;
}

.flash-info .flash-message-text {
    color: #1e40af;
}

.flash-info .flash-close {
    color: #2563eb;
}

.flash-info .flash-close:hover {
    background: rgba(59, 130, 246, 0.2);
}

/* Auto dismiss animation */
.flash-message.dismissing {
    animation: slideOutUp 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

@keyframes slideOutUp {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}

/* Responsive */
@media (min-width: 768px) {
    .flash-message {
        margin: 24px 48px;
        border-radius: 16px;
    }

    .flash-content {
        padding: 20px 24px;
    }

    .flash-icon {
        width: 48px;
        height: 48px;
        font-size: 24px;
    }

    .flash-message-text {
        font-size: 15px;
    }

    .flash-close {
        width: 36px;
        height: 36px;
        font-size: 18px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto dismiss flash messages after 5 seconds
    const flashMessages = document.querySelectorAll('.flash-message');

    flashMessages.forEach(function(message) {
        setTimeout(function() {
            message.classList.add('dismissing');
            setTimeout(function() {
                message.remove();
            }, 400);
        }, 5000);
    });
});
</script>
</div>
