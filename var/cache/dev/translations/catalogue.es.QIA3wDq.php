<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('es', array (
  'messages' => 
  array (
    'start_content' => 'Mis billeteras (%nb_wallets%/%max_wallets%)',
    'rules_accept_title' => '<b>Aceptar reglas</b>',
    'rules_accept_content' => 'Antes de empezar a usar tu Zenbot, tienes que aceptar algunas <a href=\'http://zenbot.io/\'>reglas</a>.',
    'rules_accepted' => '¡Reglas aceptadas con éxito!',
    'pincode_first' => 'Por favor, escribe un código PIN para asegurar tu cuenta:',
    'pincode_wrong' => 'Código PIN incorrecto...',
    'pincode_write' => 'Por favor, escribe tu código PIN:',
    'pincode_lost' => 'Perdí mi código PIN',
    'pincode_session_expired' => 'Tu sesión ha expirado...',
    'pincode_set' => 'Please write a pin code with a length more than 4 characters :',
    'pincode_ready_title' => '<b>¡Código PIN listo!</b>',
    'pincode_ready_content' => 'Deberíamos pedir tu código PIN para retirar o eliminar!',
    'deposit_solana_created' => 'Acabamos de crear una nueva Billetera Solana para almacenar tu gas: <i>%public_key%</i> !',
    'wallet_action_choice' => 'Elige una acción para tu billetera <b>%wallet_name%</b>

Dirección: <b><code>%wallet_address%</code></b>',
    'wallet_not_found' => 'Billetera no encontrada...',
    'wallet_list' => '<b>Tus billeteras (%wallet_count%/%max_wallets%)</b>
<i>actualizado a %update_time%</i>',
    'wallet_rename_set' => 'Escribe el nuevo nombre de tu billetera %wallet_name%:',
    'wallet_renamed' => 'Billetera renombrada con éxito',
    'wallet_already_registered' => 'Tu billetera ya está registrada, por favor escribe una nueva clave privada:',
    'wallet_connected' => '¡Tu billetera está ahora conectada!',
    'wallet_generate_select' => 'Selecciona el número de billeteras que deseas generar',
    'wallet_all_deleted' => 'Todas las billeteras han sido eliminadas...',
    'wallet_maximum_reach' => 'Lo siento, el máximo de cuentas que puedes tener es %max_wallets%',
    'wallet_new_created' => '<b>Nueva(s) billetera(s) creada(s):</b>',
    'wallet_new_created_public' => '<b>Dirección: </b>',
    'wallet_new_created_private' => '<b>Clave privada: </b>',
    'wallet_new_created_keep_safe' => '<i>Mantén tu clave privada segura</i>',
    'wallet_invalid_key' => 'Clave inválida, por favor escribe una clave privada válida:',
    'wallet_enter_private_key' => 'Por favor, escribe tu clave privada:',
    'help_title' => '<b>¿Necesitas ayuda?</b>',
    'help_content' => 'Commands :
<i>/start</i> - Start the bot
<i>/trading</i> - Open the trading menu
<i>/farming</i> - Open the farming menu
<i>/settings</i> - Open the settings menu
<i>/referrals</i> - Open the referrals menu
<i>/help</i> - You are here

If you have question, juste write your question here, our team will try to answer you asap.',
    'buy_gas_how_much' => '¿Cuánto $ quieres recargar para tu gas?',
    'buy_gas_refilled' => 'Gas recargado por %amount%$',
    'numeric_value_needed' => 'No es un valor numérico',
    'welcome_action' => 'Bienvenido, elige una acción:',
    'coming_soon' => 'Próximamente...',
    'settings_title' => '<b>Menú de configuración</b>',
    'settings_language_updated' => 'Tu idioma ha sido actualizado',
    'settings_language_choose' => 'Elige tu idioma',
    'settings_security_title' => '<b>Menú de seguridad</b>',
    'settings_security_pincode' => 'Código PIN',
    'settings_security_pincode_validity' => 'Validez del código PIN',
    'settings_security_recovery_sheet' => 'Hoja de recuperación',
    'settings_slippage_trading' => 'Slippage for trading',
    'settings_slippage_farming' => 'Slippage for farming',
    'referrals_title' => '<b>Menú de referidos</b>

',
    'referrals_content' => 'Tu código de referido es <code>%referral_code%</code>
Tu reflink: <code>https://t.me/flexabotbot?start=%referral_code%</code>

Tus referidos: <b>%nb_referrals%</b>

Refiere a tus amigos y gana el 30% de sus comisiones el primer mes. 20% en el segundo y 10% para siempre!',
    'referrer_add' => 'Por favor, escribe el código de tu referidor:',
    'referrer_add_ok' => '¡Tu referidor se ha actualizado con éxito!',
    'referrer_add_ko' => 'Código de referidor desconocido...',
    'trading_sell_no_token' => 'No tienes tokens para vender...',
    'trading_error_swap' => 'Error durante el intercambio...',
    'trading_enter_address' => '¿Introduce la dirección del token que quieres vender?',
    'trading_sell_ask_quantity' => 'Por favor, escribe la cantidad que quieres vender:',
    'trading_sell_error_positive' => 'Por favor, escribe un número positivo:',
    'farming_title' => '<b>Menú de farming</b>',
    'farming_content' => 'Describe la sección de farming',
    'button_refresh' => 'Refresh',
    'button_accept' => 'Aceptar',
    'button_refuse' => 'Rechazar',
    'button_start_bot' => 'Ok, iniciar el bot!',
    'button_return_referrals' => 'Volver al menú de referidos',
    'button_return_start' => 'Volver al menú de inicio',
    'button_return_wallet' => 'Volver al menú de billeteras',
    'button_return_settings' => 'Volver al menú de configuración',
    'button_return_settings_security' => 'Return to the security menu',
    'button_return_farming' => 'Volver al menú de farming',
    'button_wallet_connect' => 'Conectar billetera',
    'button_wallet_generate' => 'Generar billetera',
    'button_wallet_reload' => 'Recargar lista',
    'button_wallet_remove_all' => 'Eliminar todas las billeteras',
    'button_wallet_rename' => 'Renombrar',
    'button_wallet_withdraw' => 'Retirar',
    'button_wallet_private' => 'Mostrar clave privada',
    'button_select_amount' => 'Selecciona tu cantidad',
    'button_settings_slippage' => 'Deslizamiento',
    'button_settings_language' => 'Idioma',
    'button_settings_security' => 'Seguridad',
    'button_settings_mode' => 'Mode',
    'button_trading' => 'Comercio',
    'button_farming' => 'Farming',
    'button_sniping' => 'Sniping',
    'button_buy_gas' => 'Comprar gas',
    'button_settings' => 'Configuración',
    'button_help' => 'Ayuda',
    'button_referrals' => 'Referidos',
    'button_add_referrer' => 'Agregar mi código de referido',
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
