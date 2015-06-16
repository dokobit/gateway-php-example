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

`upload-file.php` - PHP code example for uploading file.  

### Create signing
- Use file token provided with file upload response.
- Add as many signers as you need.

`create-signing.php` - PHP code example for creating signing.

### Sign
Signing URL formation: https://gateway-sandbox.isign.io/signing/SIGNING_TOKEN?access_token=SIGNER_ACCESS_TOKEN.
URL is unique for each signer.  
`SIGNING_TOKEN`: token received with `signing/create` API call response.  
`SIGNER_ACCESS_TOKEN`: token received with `signing/create` API call response as parameter `signers`.  
Signers represented as associative array where key is signer's unique identifier - personal code.  

Navigate to signing URL, sign document.  
