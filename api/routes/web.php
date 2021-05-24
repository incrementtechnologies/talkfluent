<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return "heel";//view('welcome');
});
/*
  Accessing uploaded files
*/
Route::get('file/icon/{filename}', function ($filename)
{
  $path = storage_path('/icons/' . $filename);
  if (!File::exists($path)) {
      abort(404);
  }
  $file = File::get($path);
  $type = File::mimeType($path);
  $response = Response::make($file, 200);
  $response->header("Content-Type", $type);
  return $response;
});
Route::get('storage/logo/{filename}', function ($filename)
{
    $path = storage_path('/app/logo/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('storage/profile/{filename}', function ($filename)
{
    $path = storage_path('/app/profiles/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('storage/audio_files/{filename}', function ($filename)
{
    $path = storage_path('/app/audioFiles/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('storage/view_images/{filename}', function ($filename)
{
    $path = storage_path('/app/images/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('/cache', function () {
    $exitCode = Artisan::call('config:cache');
    return 'hey'.$exitCode;

    //
});
Route::get('/clear', function () {
    $exitCode = Artisan::call('config:cache');
    return 'hey'.$exitCode;

    //
});
Route::get('/migrate', function () {
    $exitCode = Artisan::call('migrate');
    return 'hey'.$exitCode;

    //
});

/* Authentication Router */
Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
Route::post('authenticate', 'AuthenticateController@authenticate');
Route::post('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
Route::get('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
Route::post('authenticate/refresh', 'AuthenticateController@refreshToken');
Route::post('authenticate/invalidate', 'AuthenticateController@deauthenticate');

//Accounts
Route::post('/accounts/create', "AccountController@create");
Route::post('/accounts/retrieve', "AccountController@retrieve");
Route::post('/accounts/update', "AccountController@update");
Route::post('/accounts/delete', "AccountController@delete");
Route::get('/accounts/test', 'AccountController@test');
Route::post('/accounts/verify', 'AccountController@verify');
Route::post('/accounts/reset_request', 'AccountController@forgetPassword');
Route::post('/accounts/update_email', 'AccountController@updateEmail');
Route::post('/accounts/update_status', 'AccountController@updateStatus');
Route::post('/accounts/update_password', 'AccountController@updatePassword');
Route::post('/accounts/resend_verification', 'AccountController@resendEmail');
Route::post('/accounts/reset_password', 'AccountController@resetPassword');

//Account Profiles
Route::get('/account_profiles/test', 'AccountProfilePictureController@test');
Route::post('/account_profiles/create', 'AccountProfilePictureController@create');
Route::post('/account_profiles/retrieve', 'AccountProfilePictureController@retrieve');
Route::post('/account_profiles/update', 'AccountProfilePictureController@update');
Route::post('/account_profiles/delete', 'AccountProfilePictureController@delete');

//Account Informations
Route::get('/account_informations/test', 'AccountInformationController@test');
Route::post('/account_informations/create', 'AccountInformationController@create');
Route::post('/account_informations/retrieve', 'AccountInformationController@retrieve');
Route::post('/account_informations/update', 'AccountInformationController@update');
Route::post('/account_informations/delete', 'AccountInformationController@delete');

//Account Informations
Route::get('/billing_informations/test', 'BillingInformationController@test');
Route::post('/billing_informations/create', 'BillingInformationController@create');
Route::post('/billing_informations/retrieve', 'BillingInformationController@retrieve');
Route::post('/billing_informations/update', 'BillingInformationController@update');
Route::post('/billing_informations/delete', 'BillingInformationController@delete');

//Test
Route::get('/test/test', 'TestController@test');
Route::post('/test/create', 'TestController@create');
Route::post('/test/retrieve', 'TestController@retrieve');
Route::post('/test/update', 'TestController@update');
Route::post('/test/delete', 'TestController@delete');

//Lesson
Route::get('/lesson/test', 'LessonController@test');
Route::post('/lesson/create', 'LessonController@create');
Route::post('/lesson/retrieve', 'LessonController@retrieve');
Route::post('/lesson/retrieve_history', 'LessonController@retrieveHistory');
Route::post('/lesson/update', 'LessonController@update');
Route::post('/lesson/delete', 'LessonController@delete');
Route::post('/lesson/dashboard', 'LessonController@dashboard');

//Lesson Tick
Route::get('/lesson_ticks/test', 'LessonTickController@test');
Route::post('/lesson_ticks/create', 'LessonTickController@create');
Route::post('/lesson_ticks/retrieve', 'LessonTickController@retrieve');
Route::post('/lesson_ticks/update', 'LessonTickController@update');
Route::post('/lesson_ticks/delete', 'LessonTickController@delete');

//Word Audio
Route::get('/word_audio/test', 'WordAudioController@test');
Route::post('/word_audio/create', 'WordAudioController@create');
Route::post('/word_audio/retrieve', 'WordAudioController@retrieve');
Route::post('/word_audio/retrieve_by_pagination', 'WordAudioController@retrieveByPagination');
Route::post('/word_audio/update', 'WordAudioController@update');
Route::post('/word_audio/delete', 'WordAudioController@delete');
Route::post('/word_audio/deleteFile/{filename}', 'WordAudioController@deleteAudioFile');
Route::post('/word_audio/dashboard', 'WordAudioController@dashboard');

//Content Audio
Route::get('/content/test', 'ContentController@test');
Route::post('/content/create', 'ContentController@create');
Route::post('/content/retrieve', 'ContentController@retrieve');
Route::post('/content/update', 'ContentController@update');
Route::post('/content/delete', 'ContentController@delete');
Route::post('/content/dashboard', 'ContentController@dashboard');

//Answer Audio
Route::get('/sentence_popup/test', 'SentencePopupController@test');
Route::post('/sentence_popup/create', 'SentencePopupController@create');
Route::post('/sentence_popup/retrieve', 'SentencePopupController@retrieve');
Route::post('/sentence_popup/update', 'SentencePopupController@update');
Route::post('/sentence_popup/delete', 'SentencePopupController@delete');
Route::post('/sentence_popup/dashboard', 'SentencePopupController@dashboard');

//Answer Audio
Route::get('/word_popup/test', 'WordPopupController@test');
Route::post('/word_popup/create', 'WordPopupController@create');
Route::post('/word_popup/retrieve', 'WordPopupController@retrieve');
Route::post('/word_popup/update', 'WordPopupController@update');
Route::post('/word_popup/delete', 'WordPopupController@delete');
Route::post('/word_popup/dashboard', 'WordPopupController@dashboard');

//Save Words
Route::get('/save/test', 'SaveController@test');
Route::post('/save/create', 'SaveController@create');
Route::post('/save/retrieve', 'SaveController@retrieve');
Route::post('/save/custom_retrieve', 'SaveController@customRetrieve');
Route::post('/save/update', 'SaveController@update');
Route::post('/save/delete', 'SaveController@delete');
Route::post('/save/retrieve_history', 'SaveController@retrieveHistory');

//Save Contents
Route::get('/save_content/test', 'SaveContentController@test');
Route::post('/save_content/create', 'SaveContentController@create');
Route::post('/save_content/retrieve', 'SaveContentController@retrieve');
Route::post('/save_content/custom_retrieve', 'SaveContentController@customRetrieve');
Route::post('/save_content/update', 'SaveContentController@update');
Route::post('/save_content/delete', 'SaveContentController@delete');
Route::post('/save_content/retrieve_history', 'SaveContentController@retrieveHistory');

//Accent Audios
Route::get('/accent_audio/test', 'AccentAudioController@test');
Route::post('/accent_audio/create', 'AccentAudioController@create');
Route::post('/accent_audio/retrieve', 'AccentAudioController@retrieve');
Route::post('/accent_audio/custom_retrieve', 'AccentAudioController@customRetrieve');
Route::post('/accent_audio/update', 'AccentAudioController@update');
Route::post('/accent_audio/delete', 'AccentAudioController@delete');

//Accent Videos
Route::get('/accent_video/test', 'AccentVideoController@test');
Route::post('/accent_video/create', 'AccentVideoController@create');
Route::post('/accent_video/retrieve', 'AccentVideoController@retrieve');
Route::post('/accent_video/custom_retrieve', 'AccentVideoController@customRetrieve');
Route::post('/accent_video/update', 'AccentVideoController@update');
Route::post('/accent_video/delete', 'AccentVideoController@delete');


//Files
Route::get('/audio_file/test', 'AudioFileController@test');
Route::post('/audio_file/create', 'AudioFileController@create');
Route::post('/audio_file/retrieve', 'AudioFileController@retrieve');
Route::post('/audio_file/update', 'AudioFileController@update');
Route::post('/audio_file/delete', 'AudioFileController@delete');

//Files
Route::get('/category/test', 'TopLessonController@test');
Route::post('/category/create', 'TopLessonController@create');
Route::post('/category/retrieve', 'TopLessonController@retrieve');
Route::post('/category/update', 'TopLessonController@update');
Route::post('/category/delete', 'TopLessonController@delete');

//Files
Route::get('/sub_category/test', 'CategoryLessonController@test');
Route::post('/sub_category/create', 'CategoryLessonController@create');
Route::post('/sub_category/retrieve', 'CategoryLessonController@retrieve');
Route::post('/sub_category/update', 'CategoryLessonController@update');
Route::post('/sub_category/delete', 'CategoryLessonController@delete');

//Blogs
Route::get('/blogs/test', 'BlogController@test');
Route::post('/blogs/create', 'BlogController@create');
Route::post('/blogs/retrieve', 'BlogController@retrieve');
Route::post('/blogs/retrieve_custom', 'BlogController@retrieveCustom');
Route::post('/blogs/update', 'BlogController@update');
Route::post('/blogs/delete', 'BlogController@delete');

//Blog Comments
Route::get('/blog_comments/test', 'BlogCommentController@test');
Route::post('/blog_comments/create', 'BlogCommentController@create');
Route::post('/blog_comments/retrieve', 'BlogCommentController@retrieve');
Route::post('/blog_comments/retrieve_by_counts', 'BlogCommentController@retrieveByNumberOfComments');
Route::post('/blog_comments/update', 'BlogCommentController@update');
Route::post('/blog_comments/delete', 'BlogCommentController@delete');

//Blog Comment Replies
Route::get('/blog_comment_replies/test', 'BlogCommentReplyController@test');
Route::post('/blog_comment_replies/create', 'BlogCommentReplyController@create');
Route::post('/blog_comment_replies/retrieve', 'BlogCommentReplyController@retrieve');
Route::post('/blog_comment_replies/retrieve_custom', 'BlogCommentReplyController@retrieveCustom');
Route::post('/blog_comment_replies/update', 'BlogCommentReplyController@update');
Route::post('/blog_comment_replies/delete', 'BlogCommentReplyController@delete');

//Contacts
Route::get('/contacts/test', 'ContactController@test');
Route::post('/contacts/create', 'ContactController@create');
Route::post('/contacts/retrieve', 'ContactController@retrieve');
Route::post('/contacts/update', 'ContactController@update');
Route::post('/contacts/delete', 'ContactController@delete');

//Images
Route::get('/images/test', 'ImageController@test');
Route::post('/images/create', 'ImageController@create');
Route::post('/images/retrieve', 'ImageController@retrieve');
Route::post('/images/update', 'ImageController@update');
Route::post('/images/delete', 'ImageController@delete');

Route::post('stripe', 'StripeController@postPaymentWithStripe');
Route::post('stripe/cancel_plans', 'StripeController@cancelPlan');

//Billing
Route::get('/billings/test', 'BillingController@test');
Route::post('/billings/create', 'BillingController@create');
Route::post('/billings/retrieve', 'BillingController@retrieve');
Route::post('/billings/update', 'BillingController@update');
Route::post('/billings/delete', 'BillingController@delete');

//Account Stripe Cards
Route::get('/stripe_cards/test', 'AccountStripeCardController@test');
Route::post('/stripe_cards/create', 'AccountStripeCardController@create');
Route::post('/stripe_cards/retrieve', 'AccountStripeCardController@retrieve');
Route::post('/stripe_cards/update', 'AccountStripeCardController@update');
Route::post('/stripe_cards/delete', 'AccountStripeCardController@delete');

//Account Payment Method
Route::get('/payment_methods/test', 'AccountPaymentMethodController@test');
Route::post('/payment_methods/create', 'AccountPaymentMethodController@create');
Route::post('/payment_methods/retrieve', 'AccountPaymentMethodController@retrieve');
Route::post('/payment_methods/update', 'AccountPaymentMethodController@update');
Route::post('/payment_methods/delete', 'AccountPaymentMethodController@delete');

//Account Payment Method
Route::get('/subscriptions/test', 'SubscriptionController@test');
Route::post('/subscriptions/create', 'SubscriptionController@create');
Route::post('/subscriptions/retrieve', 'SubscriptionController@retrieve');
Route::post('/subscriptions/update', 'SubscriptionController@update');
Route::post('/subscriptions/delete', 'SubscriptionController@delete');

//Account Payment Method
Route::get('/word_images/test', 'WordImageController@test');
Route::post('/word_images/create', 'WordImageController@create');
Route::post('/word_images/retrieve', 'WordImageController@retrieve');
Route::post('/word_images/update', 'WordImageController@update');
Route::post('/word_images/delete', 'WordImageController@delete');

// PayPal
Route::get('/paypal/execute_agreement/', 'PayPalController@executeAgreement');
Route::get('/paypal/test/', 'PayPalController@testAgreement');
Route::get('/paypal/cancel_agreement', 'PayPalController@cancelAgreement');
Route::post('/paypal/upgrade', 'PayPalController@upgrade');
Route::post('/paypal/create_on_billing', 'PayPalController@createOnBilling');
Route::post('/paypal/cancel_plans', 'PayPalController@cancelBillingPlan');

//PayPal Agreement
Route::get('/paypal_agreements/test', 'PayPalAgreementController@test');
Route::post('/paypal_agreements/create', 'PayPalAgreementController@create');
Route::post('/paypal_agreements/retrieve', 'PayPalAgreementController@retrieve');
Route::post('/paypal_agreements/update', 'PayPalAgreementController@update');
Route::post('/paypal_agreements/delete', 'PayPalAgreementController@delete');

//PayPal Agreement
Route::get('/credit_cards/test', 'CreditCardController@test');
Route::post('/credit_cards/create', 'CreditCardController@create');
Route::post('/credit_cards/retrieve', 'CreditCardController@retrieve');
Route::post('/credit_cards/update', 'CreditCardController@update');
Route::post('/credit_cards/delete', 'CreditCardController@delete');

//Stripe
Route::post('/stripes/upgrade', 'StripeController@upgrade');
Route::post('/stripes/add_payment_method', 'StripeController@addNewPaymentMethod');
Route::post('/stripes/new_subscription', 'StripeController@newSubscription');

//Coupons
Route::get('/coupons/test', 'CouponController@test');
Route::post('/coupons/create', 'CouponController@create');
Route::post('/coupons/retrieve', 'CouponController@retrieve');
Route::post('/coupons/update', 'CouponController@update');
Route::post('/coupons/delete', 'CouponController@delete');

Route::post('/billing_managers/run', 'BillingManagerController@run');

//Coupons
Route::get('/loggers/test', 'LoggerController@test');
Route::post('/loggers/create', 'LoggerController@create');
Route::post('/loggers/retrieve', 'LoggerController@retrieve');
Route::post('/loggers/update', 'LoggerController@update');
Route::post('/loggers/delete', 'LoggerController@delete');

//Feedback
Route::get('/feedbacks/test', 'FeedbackController@test');
Route::post('/feedbacks/create', 'FeedbackController@create');
Route::post('/feedbacks/retrieve', 'FeedbackController@retrieve');
Route::post('/feedbacks/update', 'FeedbackController@update');
Route::post('/feedbacks/delete', 'FeedbackController@delete');


//Email
Route::post('/emails/test', 'EmailController@testEmail');
Route::post('/emails/test_receipt', 'EmailController@testEmailReceipt');
Route::get('/emails/test_campaign', 'EmailController@testActiveCampaign');


//Ipas
Route::get('/ipas/test', 'IpaController@test');
Route::post('/ipas/create', 'IpaController@create');
Route::post('/ipas/retrieve', 'IpaController@retrieve');
Route::get('/ipas/manageIpaWord', 'IpaController@manageIpaWord');
Route::get('/ipas/manageIpaSentence', 'IpaSentenceController@manageIpaSentence');
Route::post('/ipas/update', 'IpaController@update');
Route::post('/ipas/delete', 'IpaController@delete');

//Ipas
Route::get('/ipa_classfications/test', 'IpaClassficationController@test');
Route::post('/ipa_classfications/create', 'IpaClassficationController@create');
Route::post('/ipa_classfications/retrieve', 'IpaClassficationController@retrieve');
Route::post('/ipa_classfications/update', 'IpaClassficationController@update');
Route::post('/ipa_classfications/delete', 'IpaClassficationController@delete');

