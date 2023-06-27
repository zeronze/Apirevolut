import RevolutCheckout from '@revolut/checkout';

const revolutPay = await RevolutCheckout.payments({
  publicToken: 'pk_PPKUmQFkrfwN3fIajhzBLLJMG8BG0RCZgNiuybEBbt5hlxFp' // merchant public API key
});

const paymentOptions = {
  currency: 'USD',
  totalAmount: 1000,
  redirectUrls: {
    success: 'http://revoluttest/success',
    failure: 'http://revoluttest/failure',
    cancel: 'http://revoluttest/cancel'
  },

  createOrder: async () => {
    // Call your backend here to create an order
    const order = await yourServerSideCall(); // Assuming yourServerSideCall is a valid function that makes the backend API call
    async function yourServerSideCall() {
        const url = 'http://../public/create-order.php';
      
        try {
          const response = await fetch(url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              // Inclure les données nécessaires pour créer la commande Revolut
              currency: paymentOptions.currency,
              totalAmount: paymentOptions.totalAmount
            })
          });
      
          if (!response.ok) {
            // Gérer les erreurs de réponse du serveur
            throw new Error('Erreur lors de la requête au backend.');
          }
      
          const responseData = await response.json();
      
          return responseData;
        } catch (error) {
          // Gérer les erreurs d'exception
          throw new Error('Erreur lors de la requête au backend.');
        }
      }
      
    return { publicId: order.public_id };
  },
  // You can put other optional parameters here
};

const target = document.getElementById('revolut-button');
revolutPay.mount(target, paymentOptions);
