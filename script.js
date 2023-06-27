


const paymentOptions = {
    currency: 'EUR', // 3-letter currency code
    totalAmount: 1000, // In lowest denomination e.g., cents
    

    // If you wish to implement Revolut Pay with redirect URLs (skip this option if you listen to events):
    redirectUrls: {
        success: 'http:/apirevolut/public/accueil.php',
        failure: 'http:/apirevolut/public/accueil.php',
        cancel: 'http:/apirevolut/public/accueil.php'
    },

    createOrder: async () => {
        // Call your backend here to create an order
        const orderDetails = {
            
                // Simulate an API call to your backend
                // Replace this with your actual backend API call to create the order
                
                    id: '5fd927ba-6f73-4a01-8e2b-fcd37fb629c5',
                    public_id: '94a11217-6319-4d34-8dae-a2b80b953adb',
                    type: 'PAYMENT',
                    state: 'PENDING',
                    created_at: '2020-10-15T07:46:40.648108Z',
                    updated_at: '2020-10-15T07:46:40.648108Z',
                    capture_mode: 'AUTOMATIC',
                    merchant_order_ext_ref: 'Order test',
                    email: 'johndoe001@gmail.com',
                    order_amount: {
                        value: 1000,
                        currency: 'EUR'
                    },
                    checkout_url: 'https://business.revolut.com/payment-link/uLHGQVGNx_ZRDV14z5VFIA'
               
            
        };

        // For more information, see: https://developer.revolut.com/docs/merchant/create-order
        

        return { publicId: orderDetails.public_id };
    },

    // You can put other optional parameters here
    
};

const target = document.getElementById("revolut-button");

async function initializeRevolut() {
    const { revolutPay } = await RevolutCheckout.payments({ locale: 'en', mode: 'sandbox', publicToken: 'pk_PPKUmQFkrfwN3fIajhzBLLJMG8BG0RCZgNiuybEBbt5hlxFp' });
    revolutPay.mount(target, paymentOptions);
}

initializeRevolut();
