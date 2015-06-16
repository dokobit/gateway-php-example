# iSign.io Gateway API PHP Example

## Example configuration
- Copy `config.php.dist` to `config.php`.
- Set `$accessToken` variable in `config.php`.

## Flow

### Upload file
- Upload file you want to sign* and get uploaded file token.
- Check file upload status. If status `uploaded`\*\*, continue.

\* You should provide file URL which would be accessible for Gateway API.  
\*\* File status must be checked before creating signing.

`upload-file.php` - PHP code example for uploading file. Could be run from web or CLI.

### Create signing
- Use file token provided with file upload response.
- Add as many signers as you need.

`create-signing.php` - PHP code example for creating signing. Could be run from web or CLI. **Before running: edit file, and change `$file['token']` value, with token you get after running `upload-file.php`**.

### Sign
Signing URL formation: https://gateway-sandbox.isign.io/signing/SIGNING_TOKEN?access_token=SIGNER_ACCESS_TOKEN.
URL is unique for each signer.  
`SIGNING_TOKEN`: token received with `signing/create` API call response.  
`SIGNER_ACCESS_TOKEN`: token received with `signing/create` API call response as parameter `signers`.  
Signers represented as associative array where key is signer's unique identifier - personal code.  

Navigate to signing URL, sign document.  


### Retrieving signed document
After document signing postback calls are trigered, if 
`callback_url` was set while creating signing.  
There are two types of postback calls:
1. After signer has signed document - `signer_signed`.
2. After signing has been completed (all signers successfully signed) - `signing_completed`.

`signing-finished-postback.php` - PHP code example for handling postback calls.
File should be placed in public web directory, accessible for Gateway API.

To retrieve signed document using these examples, your will need:
- Put `signing-finished-postback.php` in public web directory, accessible for Gateway API.
- Set `$postbackUrl` parameter in `config.php` with URL where the `signing-finished-postback.php` will be available. For eg. `http://your-public-host/signing-finished-postback.php`.
- Create signing.
- Sign.
- Information about signed document will be sent to postback URL. `signing-finished-postback.php` will handle postback and signed file will be stored in directory where `signing-finished-postback.php` is located.
- Log file `postback.log` containing postback information, will be placed in the same directory as postback handler.
