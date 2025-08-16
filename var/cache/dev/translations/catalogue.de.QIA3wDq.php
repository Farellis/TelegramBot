<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('de', array (
  'messages' => 
  array (
    'start_content' => 'Meine Geldbörsen (%nb_wallets%/%max_wallets%)',
    'rules_accept_title' => '<b>Regeln akzeptieren</b>',
    'rules_accept_content' => 'Bevor Sie Ihren Zenbot nutzen können, müssen Sie einige <a href=\'http://zenbot.io/\'>Regeln</a> akzeptieren.',
    'rules_accepted' => 'Regeln erfolgreich akzeptiert!',
    'pincode_first' => 'Bitte geben Sie einen PIN-Code ein, um Ihr Konto zu sichern:',
    'pincode_wrong' => 'Falscher PIN-Code...',
    'pincode_write' => 'Bitte geben Sie Ihren PIN-Code ein:',
    'pincode_lost' => 'Ich habe meinen PIN-Code verloren',
    'pincode_session_expired' => 'Ihre Sitzung ist abgelaufen...',
    'pincode_set' => 'Please write a pin code with a length more than 4 characters :',
    'pincode_ready_title' => '<b>PIN-Code ist fertig!</b>',
    'pincode_ready_content' => 'Wir werden Ihren PIN-Code für Abhebungen oder Löschungen fragen!',
    'deposit_solana_created' => 'Wir haben gerade ein neues Solana-Wallet erstellt, um Ihr Gas zu speichern: <i>%public_key%</i>!',
    'wallet_action_choice' => 'Wählen Sie eine Aktion für Ihre Geldbörse <b>%wallet_name%</b>

Adresse: <b><code>%wallet_address%</code></b>',
    'wallet_not_found' => 'Geldbörse nicht gefunden...',
    'wallet_list' => '<b>Ihre Geldbörsen (%wallet_count%/%max_wallets%)</b>
<i>aktualisiert um %update_time%</i>',
    'wallet_rename_set' => 'Geben Sie den neuen Namen Ihrer Geldbörse %wallet_name% ein:',
    'wallet_renamed' => 'Geldbörse erfolgreich umbenannt',
    'wallet_already_registered' => 'Ihre Geldbörse ist bereits registriert, bitte geben Sie einen neuen privaten Schlüssel ein:',
    'wallet_connected' => 'Ihre Geldbörse ist jetzt verbunden!',
    'wallet_generate_select' => 'Wählen Sie die Anzahl der Geldbörsen, die Sie generieren möchten',
    'wallet_all_deleted' => 'Alle Geldbörsen sind gelöscht...',
    'wallet_maximum_reach' => 'Entschuldigung, die maximale Anzahl von Konten, die Sie halten können, ist %max_wallets%',
    'wallet_new_created' => '<b>Neue Geldbörse(n) erstellt:</b>',
    'wallet_new_created_public' => '<b>Adresse: </b>',
    'wallet_new_created_private' => '<b>Privater Schlüssel: </b>',
    'wallet_new_created_keep_safe' => '<i>Bewahren Sie Ihren privaten Schlüssel sicher auf</i>',
    'wallet_invalid_key' => 'Ungültiger Schlüssel, bitte geben Sie einen gültigen privaten Schlüssel ein:',
    'wallet_enter_private_key' => 'Bitte geben Sie Ihren privaten Schlüssel ein:',
    'help_title' => '<b>Brauchen Sie Hilfe?</b>',
    'help_content' => 'Commands :
<i>/start</i> - Start the bot
<i>/trading</i> - Open the trading menu
<i>/farming</i> - Open the farming menu
<i>/settings</i> - Open the settings menu
<i>/referrals</i> - Open the referrals menu
<i>/help</i> - You are here

If you have question, juste write your question here, our team will try to answer you asap.',
    'buy_gas_how_much' => 'Wie viel $ möchten Sie für Ihr Gas aufladen?',
    'buy_gas_refilled' => 'Gas für %amount%$ aufgefüllt',
    'numeric_value_needed' => 'Das ist kein numerischer Wert',
    'welcome_action' => 'Willkommen, wählen Sie eine Aktion:',
    'coming_soon' => 'Demnächst verfügbar...',
    'settings_title' => '<b>Einstellungsmenü</b>',
    'settings_language_updated' => 'Ihre Sprache wurde aktualisiert',
    'settings_language_choose' => 'Wählen Sie Ihre Sprache',
    'settings_security_title' => '<b>Sicherheitsmenü</b>',
    'settings_security_pincode' => 'PIN-Code',
    'settings_security_pincode_validity' => 'Gültigkeit des PIN-Codes',
    'settings_security_recovery_sheet' => 'Wiederherstellungsblatt',
    'settings_slippage_trading' => 'Slippage for trading',
    'settings_slippage_farming' => 'Slippage for farming',
    'referrals_title' => '<b>Empfehlungsmenü</b>

',
    'referrals_content' => 'Ihr Empfehlungscode ist <code>%referral_code%</code>
Ihr Reflink: <code>https://t.me/flexabotbot?start=%referral_code%</code>

Ihre Empfehlungen: <b>%nb_referrals%</b>

Empfehlen Sie Ihre Freunde und verdienen Sie 30% ihrer Gebühren im ersten Monat. 20% im zweiten und 10% für immer!',
    'referrer_add' => 'Bitte geben Sie den Code Ihres Empfehlers ein:',
    'referrer_add_ok' => 'Ihr Empfehler wurde erfolgreich aktualisiert!',
    'referrer_add_ko' => 'Unbekannter Empfehlercode...',
    'trading_sell_no_token' => 'Sie haben keine Tokens zum Verkaufen...',
    'trading_error_swap' => 'Fehler beim Tauschen...',
    'trading_enter_address' => 'Geben Sie die Adresse des Tokens ein, das Sie verkaufen möchten?',
    'trading_sell_ask_quantity' => 'Bitte geben Sie die Menge ein, die Sie verkaufen möchten:',
    'trading_sell_error_positive' => 'Bitte geben Sie eine positive Zahl ein:',
    'farming_title' => '<b>Farming-Menü</b>',
    'farming_content' => 'Beschreiben Sie den Farming-Bereich',
    'button_refresh' => 'Refresh',
    'button_accept' => 'Akzeptieren',
    'button_refuse' => 'Ablehnen',
    'button_start_bot' => 'Ok, starten Sie den Bot!',
    'button_return_referrals' => 'Zurück zum Empfehlungsmenü',
    'button_return_start' => 'Zurück zum Startmenü',
    'button_return_wallet' => 'Zurück zum Geldbörsen-Menü',
    'button_return_settings' => 'Zurück zum Einstellungsmenü',
    'button_return_settings_security' => 'Return to the security menu',
    'button_return_farming' => 'Zurück zum Farming-Menü',
    'button_return_trading' => 'Zurück zum Trading-Menü',
    'button_wallet_connect' => 'Geldbörse verbinden',
    'button_wallet_generate' => 'Geldbörse generieren',
    'button_wallet_reload' => 'Liste neu laden',
    'button_wallet_remove_all' => 'Alle Geldbörsen entfernen',
    'button_wallet_rename' => 'Umbenennen',
    'button_wallet_withdraw' => 'Abheben',
    'button_wallet_private' => 'Privaten Schlüssel anzeigen',
    'button_select_amount' => 'Wählen Sie Ihren Betrag',
    'button_settings_slippage' => 'Slippage',
    'button_settings_language' => 'Sprache',
    'button_settings_security' => 'Sicherheit',
    'button_settings_mode' => 'Mode',
    'button_trading' => 'Handel',
    'button_farming' => 'Farming',
    'button_sniping' => 'Sniping',
    'button_buy_gas' => 'Gas kaufen',
    'button_settings' => 'Einstellungen',
    'button_help' => 'Hilfe',
    'button_referrals' => 'Empfehlungen',
    'button_add_referrer' => 'Meinen Empfehlercode hinzufügen',
  ),
));

$catalogueEn = new MessageCatalogue('en', array (
  'messages' => 
  array (
    'start_content' => 'My wallets (%nb_wallets%/%max_wallets%)',
    'rules_accept_title' => '<b>Accept rules</b>',
    'rules_accept_content' => 'Before start to use your Zenbot, you have to accept some <a href=\'http://zenbot.io/\'>rules</a>.',
    'rules_accepted' => 'Rules accepted with success !',
    'pincode_first' => 'Please write a pin code to secure your account :',
    'pincode_wrong' => 'Wrong pincode...',
    'pincode_write' => 'Please write your pincode :',
    'pincode_lost' => 'I lost my pincode',
    'pincode_session_expired' => 'Your session has expired...',
    'pincode_set' => 'Please write a pin code with a length more than 4 characters :',
    'pincode_ready_title' => '<b>Pin code is ready !</b>',
    'pincode_ready_content' => 'We should ask your pin code for withdraw or delete !',
    'deposit_solana_created' => 'We just created a new Solana Wallet to stock your gas : <i>%public_key%</i> !',
    'wallet_action_choice' => 'Choose an action for your wallet <b>%wallet_name%</b>

Address: <b><code>%wallet_address%</code></b>',
    'wallet_not_found' => 'Wallet not found...',
    'wallet_list' => '<b>Your wallets (%wallet_count%/%max_wallets%)</b>
<i>updated at %update_time%</i>',
    'wallet_rename_set' => 'Write the new name of your wallet %wallet_name% :',
    'wallet_renamed' => 'Wallet is renamed with success',
    'wallet_already_registered' => 'Your wallet is already registered, please write a new private key :',
    'wallet_connected' => 'Your wallet is now connected !',
    'wallet_generate_select' => 'Select the number of wallet that you want generate',
    'wallet_all_deleted' => 'All wallets are deleted...',
    'wallet_maximum_reach' => 'Sorry, the maximum account that you can hold is %max_wallets%',
    'wallet_new_created' => '<b>New wallet%s created:</b>',
    'wallet_new_created_public' => '<b>Address: </b>',
    'wallet_new_created_private' => '<b>Private key: </b>',
    'wallet_new_created_keep_safe' => '<i>Keep your private key safe</i>',
    'wallet_invalid_key' => 'Invalid key, please write a valid private key :',
    'wallet_enter_private_key' => 'Please write your private key :',
    'help_title' => '<b>Do you need help ?</b>',
    'help_content' => 'Commands :
<i>/start</i> - Start the bot
<i>/trading</i> - Open the trading menu
<i>/farming</i> - Open the farming menu
<i>/settings</i> - Open the settings menu
<i>/referrals</i> - Open the referrals menu
<i>/help</i> - You are here

If you have question, juste write your question here, our team will try to answer you asap.',
    'buy_gas_how_much' => 'How much $ do you want to refill your gas ?',
    'buy_gas_refilled' => 'Gas just refilled for %amount%$',
    'numeric_value_needed' => 'It\'s not a numeric value',
    'welcome_action' => 'Welcome, choose an action:',
    'coming_soon' => 'Coming soon...',
    'settings_title' => '<b>Settings menu</b>',
    'settings_language_updated' => 'Your language is updated',
    'settings_language_choose' => 'Choose your language',
    'settings_security_title' => '<b>Security menu</b>',
    'settings_security_pincode' => 'PIN code',
    'settings_security_pincode_validity' => 'Pincode validity',
    'settings_security_recovery_sheet' => 'Recovery sheet',
    'settings_slippage_trading' => 'Slippage for trading',
    'settings_slippage_farming' => 'Slippage for farming',
    'referrals_title' => '<b>Referrals menu</b>

',
    'referrals_content' => 'Your referral code is <code>%referral_code%</code>
Your reflink: <code>https://t.me/flexabotbot?start=%referral_code%</code>

Your referrals: <b>%nb_referrals%</b>

Unlock the power of your referral program and boost your earnings!
When you refer friends to use our bot, you\'ll earn 10% of the transaction fees they generate.',
    'referrer_add' => 'Please write the code of your referrer :',
    'referrer_add_ok' => 'Your referrer is updated with success !',
    'referrer_add_ko' => 'Unknow referrer code...',
    'trading_sell_no_token' => 'You don\'t have tokens to sell...',
    'trading_error_swap' => 'Error during swap...',
    'trading_enter_address' => 'Enter the token adress that you want to sell ?',
    'trading_sell_ask_quantity' => 'Please write the quantity that you want to sell :',
    'trading_sell_error_positive' => 'Please write a positive number :',
    'farming_title' => '<b>Farming menu</b>',
    'farming_content' => 'Describe the farming section',
    'button_refresh' => 'Refresh',
    'button_accept' => 'Accept',
    'button_refuse' => 'Refuse',
    'button_start_bot' => 'Ok, start the bot !',
    'button_return_referrals' => 'Return to the referrals menu',
    'button_return_start' => 'Return to the start menu',
    'button_return_wallet' => 'Return to the wallet menu',
    'button_return_settings' => 'Return to the settings menu',
    'button_return_settings_security' => 'Return to the security menu',
    'button_return_farming' => 'Return to the farming menu',
    'button_return_trading' => 'Return to the trading menu',
    'button_wallet_connect' => 'Connect wallet',
    'button_wallet_generate' => 'Generate wallet',
    'button_wallet_reload' => 'Reload list',
    'button_wallet_remove_all' => 'Remove all wallets',
    'button_wallet_rename' => 'Rename',
    'button_wallet_withdraw' => 'Withdraw',
    'button_wallet_private' => 'Show private key',
    'button_select_amount' => 'Select your amount',
    'button_settings_slippage' => 'Slippage',
    'button_settings_language' => 'Language',
    'button_settings_security' => 'Security',
    'button_settings_mode' => 'Mode',
    'button_trading' => 'Trading',
    'button_farming' => 'Farming',
    'button_sniping' => 'Sniping',
    'button_buy_gas' => 'Buy gas',
    'button_settings' => 'Settings',
    'button_help' => 'Help',
    'button_referrals' => 'Referrals',
    'button_add_referrer' => 'Add my referrer code',
  ),
));
$catalogue->addFallbackCatalogue($catalogueEn);

return $catalogue;
