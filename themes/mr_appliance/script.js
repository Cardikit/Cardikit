// Copy to clipboard functionality
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy:', err);
        // Fallback for older browsers
        fallbackCopyToClipboard(text);
    });
}

// Fallback copy method for older browsers
function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    document.body.appendChild(textArea);
    textArea.select();
    try {
        document.execCommand('copy');
        showNotification('Copied to clipboard!');
    } catch (err) {
        console.error('Fallback copy failed:', err);
        showNotification('Failed to copy');
    }
    document.body.removeChild(textArea);
}

// Show notification
function showNotification(message) {
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 30px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #F47E6C 0%, #FF8B7A 100%);
        color: white;
        padding: 16px 32px;
        border-radius: 12px;
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 8px 24px rgba(244, 126, 108, 0.4);
        z-index: 1000;
        animation: slideDown 0.3s ease-out;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideUp 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}

// Download vCard
function downloadVCard() {
    const vcard = `BEGIN:VCARD
VERSION:3.0
FN:John Anderson
TITLE:Service Manager
ORG:Mr Appliance of Reno
TEL;TYPE=WORK,VOICE:775.384.8400
EMAIL;TYPE=PREF,INTERNET:mraofreno@gmail.com
URL:MrAppliance.com/Reno
NOTE:Serving Greater Reno Area
END:VCARD`;

    const blob = new Blob([vcard], { type: 'text/vcard' });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'john-anderson-mrappliance-reno.vcf';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
    
    showNotification('Contact downloaded!');
}

// Share card
function shareCard() {
    if (navigator.share) {
        navigator.share({
            title: 'Mr. Appliance of Reno - John Anderson',
            text: 'Service Manager at Mr. Appliance of Reno\nPhone: 775.384.8400\nEmail: mraofreno@gmail.com\nServing Greater Reno Area',
            url: window.location.href
        }).then(() => {
            showNotification('Card shared successfully!');
        }).catch(err => {
            // User cancelled or error occurred
            if (err.name !== 'AbortError') {
                console.error('Share failed:', err);
                copyToClipboard(window.location.href);
            }
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        copyToClipboard(window.location.href);
        showNotification('Link copied to clipboard!');
    }
}

// Parallax effect for floating elements
document.addEventListener('mousemove', (e) => {
    const floatElements = document.querySelectorAll('.float-element');
    const x = e.clientX / window.innerWidth;
    const y = e.clientY / window.innerHeight;
    
    floatElements.forEach((el, index) => {
        const speed = (index + 1) * 10;
        const xMove = (x - 0.5) * speed;
        const yMove = (y - 0.5) * speed;
        el.style.transform = `translate(${xMove}px, ${yMove}px)`;
    });
});

// Smooth scroll for any internal links (if added later)
document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Add keyboard accessibility
document.addEventListener('keydown', (e) => {
    // Allow Enter key to trigger click on contact items
    if (e.key === 'Enter' && e.target.classList.contains('contact-item')) {
        e.target.click();
    }
    
    // Allow Enter key to trigger button clicks
    if (e.key === 'Enter' && e.target.classList.contains('btn')) {
        e.target.click();
    }
});

// Make contact items keyboard accessible
document.addEventListener('DOMContentLoaded', () => {
    const contactItems = document.querySelectorAll('.contact-item');
    contactItems.forEach(item => {
        item.setAttribute('tabindex', '0');
        item.setAttribute('role', 'button');
    });
    
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.setAttribute('tabindex', '0');
    });
});
