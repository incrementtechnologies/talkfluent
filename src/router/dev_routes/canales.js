
export default{
  routes: [{
    path: '/canales',
    name: 'canales',
    component: resolve => require(['modules/test/Canales.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/signup/:plan?',
    name: 'registration',
    component: resolve => require(['modules/home/Signup.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/login',
    name: 'loginAccount',
    component: resolve => require(['modules/home/LogIn.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/request_reset',
    name: 'requestReset',
    component: resolve => require(['modules/home/RequestReset.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/reset_password/:username/:code',
    name: 'resetPassword',
    component: resolve => require(['modules/home/ResetPassword.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/verification/:email',
    name: 'verification',
    component: resolve => require(['modules/home/Verification.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/account_verification/:username?/:code?',
    name: 'accountVerification',
    component: resolve => require(['modules/home/AccountVerification.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/word_translation',
    name: 'wordTranslation',
    component: resolve => require(['modules/translation/word.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/lesson_management',
    name: 'lessonManagement',
    component: resolve => require(['modules/lesson/LessonManagement.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/audio_upload',
    name: 'uploadAudio',
    component: resolve => require(['modules/lesson/uploadAudio.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: resolve => require(['modules/dashboard/dashboard.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/word_audio',
    name: 'wordAudio',
    component: resolve => require(['modules/lesson/WordAudio.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/content_management',
    name: 'contentManagement',
    component: resolve => require(['modules/lesson/ContentManagementv2.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/sentence_popup',
    name: 'sentencePopup',
    component: resolve => require(['modules/lesson/SentencePopup.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/word_popup',
    name: 'wordPopup',
    component: resolve => require(['modules/lesson/WordPopup.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/dashboard_temp',
    name: 'dashboardTemp',
    component: resolve => require(['modules/dashboard/dashboard.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/accent_audio',
    name: 'accentAudio',
    component: resolve => require(['modules/lesson/AccentAudio.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/accent_video',
    name: 'accentVideo',
    component: resolve => require(['modules/lesson/AccentVideo.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/already_known/:parameter',
    name: 'alreadyKnown',
    component: resolve => require(['modules/history/History.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/audio_files',
    name: 'audioFiles',
    component: resolve => require(['modules/audio/AudioFile.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/category_lesson',
    name: 'categoryLesson',
    component: resolve => require(['modules/lesson/CategoryLesson.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/subcategory_lesson',
    name: 'subCategoryLesson',
    component: resolve => require(['modules/lesson/SubCategoryLesson.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/checker_test',
    name: 'checkerTest',
    component: resolve => require(['modules/checker/test.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/blog',
    name: 'blog',
    component: resolve => require(['modules/blog/Blog.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/inquiries',
    name: 'inquiries',
    component: resolve => require(['modules/inquiry/Inquiry.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/images',
    name: 'images',
    component: resolve => require(['modules/images/Images.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/my_plan',
    name: 'my_plan',
    component: resolve => require(['modules/billing/Billing.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/subscription',
    name: 'subscription',
    component: resolve => require(['modules/subscription/Subscription.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/word_images',
    name: 'wordImages',
    component: resolve => require(['modules/word/WordImages.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/paypal/',
    name: 'paypal',
    component: resolve => require(['modules/paypal/Paypal.vue'], resolve),
    meta: {
      tokenRequired: false
    },
    props: (route) => ({
      token: route.query.token
    })
  },
  {
    path: '/plans',
    name: 'plans',
    component: resolve => require(['modules/billing/PaypalPlans.vue'], resolve),
    meta: {
      tokenRequired: false
    },
    props: (route) => ({
      token: route.query.token
    })
  },
  {
    path: '/uplans',
    name: 'uplans',
    component: resolve => require(['modules/billing/UpgradePlans.vue'], resolve),
    meta: {
      tokenRequired: false
    },
    props: (route) => ({
      token: route.query.token
    })
  },
  {
    path: '/my_profile',
    name: 'my_profile',
    component: resolve => require(['modules/account/Update.vue'], resolve),
    meta: {
      tokenRequired: true
    },
    props: (route) => ({
      token: route.query.token
    })
  },
  {
    path: '/logger',
    name: 'logger',
    component: resolve => require(['modules/logger/Logger.vue'], resolve),
    meta: {
      tokenRequired: true
    },
    props: (route) => ({
      token: route.query.token
    })
  },
  {
    path: '/feedback',
    name: 'feedback',
    component: resolve => require(['modules/feedback/Feedback.vue'], resolve),
    meta: {
      tokenRequired: true
    },
    props: (route) => ({
      token: route.query.token
    })
  },
  {
    path: '/ipas',
    name: 'ipas',
    component: resolve => require(['modules/lesson/Ipas.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/ipa_classifications',
    name: 'ipaClassifications',
    component: resolve => require(['modules/lesson/IpaClassification.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  }
  ]
}
