import CONFIG from 'config.js'
export default{
  currency: 'usd',
  flag: false,
  stripe: {
    live_pk: 'pk_test_MGWJovoYWYwfmZkZ9EAWXC7y',
    live_sk: 'sk_test_pY1avuNBquSS1njcuKM62TEt',
    dev_pk: 'pk_test_0unvKSqNiOG2boLISl8xy6pU',
    dev_sk: 'sk_test_S9z3DllqwDdKXaGkgtUuA07V'
  },
  paypal: {
    mode: 'sandbox',
    return_url: CONFIG.BACKEND_URL + '/paypal/execute_agreement',
    cancel_url: CONFIG.BACKEND_URL + '/paypal/cancel_agreement'
  }
}
