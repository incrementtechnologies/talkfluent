let MONTHLY = 49
let ANNUALLY = 19
let PAUSE = 9
let TRIAL = 1
export default{
  monthly: MONTHLY,
  annually: ANNUALLY,
  pause: PAUSE,
  stripe: {
    product: 'pk_test_MGWJovoYWYwfmZkZ9EAWXC7y',
    plan: {
      monthly: {
        id: 'plan_Dtl1a4juu3hloN',
        price: (MONTHLY * 100),
        trial_price: (TRIAL * 100)
      },
      annually: {
        id: 'plan_Dtl2y2kAqSo06W',
        price: (ANNUALLY * 100),
        trial_price: (TRIAL * 100)
      },
      pause: {
        id: 'plan_DtSRxasTll8zV6',
        price: (PAUSE * 100),
        trial_price: 0
      }
    }
  },
  paypal: {
    plan: {
      monthly: {
        price: MONTHLY,
        trial_price: TRIAL
      },
      annually: {
        price: ANNUALLY,
        trial_price: TRIAL
      },
      pause: {
        price: PAUSE,
        trial_price: 0
      }
    }
  }
}
