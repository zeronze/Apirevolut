const paymentOptions = {
  currency: 'EUR', // Code de devise à 3 lettres
  totalAmount: 1000, // En plus petite dénomination, par exemple les centimes
  
  // Si vous souhaitez implémenter Revolut Pay avec des URL de redirection (ignorez cette option si vous écoutez les événements) :
  redirectUrls: {
    success: 'http://revoluttest/success',
    failure: 'http://revoluttest/failure',
    cancel: 'http://revoluttest/cancel'
  },

  createOrder: async () => {
    // Appelez votre backend ici pour créer une commande
    const orderDetails = await yourServerSideCall();
    
    async function yourServerSideCall() {
      const url = 'http://public/create-order.php';
      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            currency: paymentOptions.currency,
            totalAmount: paymentOptions.totalAmount
          })
        });

        if (!response.ok) {
          throw new Error('Erreur lors de la requête au backend.');
        }

        const responseData = await response.json();
        return responseData;
      } catch (error) {
        throw new Error('Erreur lors de la requête au backend.');
      }
    }

    return { publicId: orderDetails.public_id };
  },
  
  // Vous pouvez ajouter d'autres paramètres facultatifs ici
};

const target = document.getElementById("revolut-button");

async function initializeRevolut() {
  const { revolutPay } = await RevolutCheckout.payments({ locale: 'en', mode: 'sandbox', publicToken: 'pk_PPKUmQFkrfwN3fIajhzBLLJMG8BG0RCZgNiuybEBbt5hlxFp' });
  revolutPay.mount(target, paymentOptions);
}

initializeRevolut();
