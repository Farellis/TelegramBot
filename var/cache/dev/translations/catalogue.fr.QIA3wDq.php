<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('fr', array (
  'messages' => 
  array (
    'start_content' => 'Mes portefeuilles (%nb_wallets%/%max_wallets%)',
    'rules_accept_title' => '<b>Accepter les règles</b>',
    'rules_accept_content' => 'Avant de commencer à utiliser votre Zenbot, vous devez accepter certaines <a href=\'http://zenbot.io/\'>règles</a>.',
    'rules_accepted' => 'Règles acceptées avec succès !',
    'pincode_first' => 'Veuillez écrire un code PIN pour sécuriser votre compte :',
    'pincode_wrong' => 'Code PIN incorrect...',
    'pincode_write' => 'Veuillez écrire votre code PIN :',
    'pincode_lost' => 'J\'ai perdu mon code PIN',
    'pincode_session_expired' => 'Votre session a expiré...',
    'pincode_set' => 'Please write a pin code with a length more than 4 characters :',
    'pincode_ready_title' => '<b>Code PIN prêt !</b>',
    'pincode_ready_content' => 'Nous devrions demander votre code PIN pour les retraits ou les suppressions !',
    'deposit_solana_created' => 'Nous venons de créer un nouveau portefeuille Solana pour stocker votre gaz : <i>%public_key%</i> !',
    'wallet_action_choice' => 'Choisissez une action pour votre portefeuille <b>%wallet_name%</b>

Adresse : <b><code>%wallet_address%</code></b>',
    'wallet_not_found' => 'Portefeuille non trouvé...',
    'wallet_list' => '<b>Vos portefeuilles (%wallet_count%/%max_wallets%)</b>
<i>mis à jour à %update_time%</i>',
    'wallet_rename_set' => 'Écrivez le nouveau nom de votre portefeuille %wallet_name% :',
    'wallet_renamed' => 'Portefeuille renommé avec succès',
    'wallet_already_registered' => 'Votre portefeuille est déjà enregistré, veuillez écrire une nouvelle clé privée :',
    'wallet_connected' => 'Votre portefeuille est maintenant connecté !',
    'wallet_generate_select' => 'Sélectionnez le nombre de portefeuilles que vous souhaitez générer',
    'wallet_all_deleted' => 'Tous les portefeuilles sont supprimés...',
    'wallet_maximum_reach' => 'Désolé, le nombre maximum de comptes que vous pouvez détenir est de %max_wallets%',
    'wallet_new_created' => '<b>Nouveau(x) portefeuille(s) créé(s) :</b>',
    'wallet_new_created_public' => '<b>Adresse : </b>',
    'wallet_new_created_private' => '<b>Clé privée : </b>',
    'wallet_new_created_keep_safe' => '<i>Gardez votre clé privée en sécurité</i>',
    'wallet_invalid_key' => 'Clé invalide, veuillez écrire une clé privée valide :',
    'wallet_enter_private_key' => 'Veuillez écrire votre clé privée :',
    'help_title' => '<b>Besoin d\'aide ?</b>',
    'help_content' => 'Commands :
<i>/start</i> - Start the bot
<i>/trading</i> - Open the trading menu
<i>/farming</i> - Open the farming menu
<i>/settings</i> - Open the settings menu
<i>/referrals</i> - Open the referrals menu
<i>/help</i> - You are here

If you have question, juste write your question here, our team will try to answer you asap.',
    'buy_gas_how_much' => 'Combien de $ souhaitez-vous recharger pour votre gaz ?',
    'buy_gas_refilled' => 'Gaz rechargé pour %amount%$',
    'numeric_value_needed' => 'Ce n\'est pas une valeur numérique',
    'welcome_action' => 'Bienvenue, choisissez une action :',
    'coming_soon' => 'Bientôt disponible...',
    'settings_title' => '<b>Menu des paramètres</b>',
    'settings_language_updated' => 'Votre langue a été mise à jour',
    'settings_language_choose' => 'Choisissez votre langue',
    'settings_security_title' => '<b>Menu de sécurité</b>',
    'settings_security_pincode' => 'Code PIN',
    'settings_security_pincode_validity' => 'Validité du code PIN',
    'settings_security_recovery_sheet' => 'Feuille de récupération',
    'settings_slippage_trading' => 'Slippage for trading',
    'settings_slippage_farming' => 'Slippage for farming',
    'referrals_title' => '<b>Menu de parrainage</b>

',
    'referrals_content' => 'Votre code de parrainage est <code>%referral_code%</code>
Votre reflink : <code>https://t.me/flexabotbot?start=%referral_code%</code>

Vos parrainages : <b>%nb_referrals%</b>

Parrainez vos amis et gagnez 30% de leurs frais le premier mois. 20% le deuxième et 10% à vie !',
    'referrer_add' => 'Veuillez écrire le code de votre parrain :',
    'referrer_add_ok' => 'Votre parrain a été mis à jour avec succès !',
    'referrer_add_ko' => 'Code de parrain inconnu...',
    'trading_sell_no_token' => 'Vous n\'avez pas de jetons à vendre...',
    'trading_error_swap' => 'Erreur lors de l\'échange...',
    'trading_enter_address' => 'Entrez l\'adresse du jeton que vous souhaitez vendre ?',
    'trading_sell_ask_quantity' => 'Veuillez écrire la quantité que vous souhaitez vendre :',
    'trading_sell_error_positive' => 'Veuillez écrire un nombre positif :',
    'farming_title' => '<b>Menu de farming</b>',
    'farming_content' => 'Décrire la section de farming',
    'button_refresh' => 'Refresh',
    'button_accept' => 'Accepter',
    'button_refuse' => 'Refuser',
    'button_start_bot' => 'Ok, démarrer le bot !',
    'button_return_referrals' => 'Retour au menu de parrainage',
    'button_return_start' => 'Retour au menu de démarrage',
    'button_return_wallet' => 'Retour au menu du portefeuille',
    'button_return_settings' => 'Retour au menu des paramètres',
    'button_return_settings_security' => 'Return to the security menu',
    'button_return_farming' => 'Retour au menu de farming',
    'button_return_trading' => 'Retour au menu de trading',
    'button_wallet_connect' => 'Connecter le portefeuille',
    'button_wallet_generate' => 'Générer un portefeuille',
    'button_wallet_reload' => 'Recharger la liste',
    'button_wallet_remove_all' => 'Supprimer tous les portefeuilles',
    'button_wallet_rename' => 'Renommer',
    'button_wallet_withdraw' => 'Retirer',
    'button_wallet_private' => 'Afficher la clé privée',
    'button_select_amount' => 'Sélectionnez votre montant',
    'button_settings_slippage' => 'Glissement',
    'button_settings_language' => 'Langue',
    'button_settings_security' => 'Sécurité',
    'button_settings_mode' => 'Mode',
    'button_trading' => 'Commerce',
    'button_farming' => 'Farming',
    'button_sniping' => 'Sniping',
    'button_buy_gas' => 'Acheter du gaz',
    'button_settings' => 'Paramètres',
    'button_help' => 'Aide',
    'button_referrals' => 'Parrainages',
    'button_add_referrer' => 'Ajouter mon code de parrain',
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
