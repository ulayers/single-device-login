# Single Device Login

## Description

The Single Device Login plugin enhances security by ensuring that each user account can only log in from a registered device. If a login attempt is made from an unrecognized device, it is blocked, preventing unauthorized account sharing and enhancing user security. Administrators can reset the device registration if needed.

## Features

- **Device Lock**: Prevents unauthorized logins by ensuring that each user account can only be accessed from a registered device. If a login attempt is made from a different device, it is blocked, requiring administrative intervention to reset the device association.
- **Security Enhancement**: Prevents unauthorized sharing of user credentials.
- **Easy to Use**: Seamlessly integrates with the WordPress user authentication system.

## Installation

1. **Upload Plugin**: Upload the `single-device-login` folder to the `/wp-content/plugins/` directory.
2. **Activate Plugin**: Activate the plugin through the 'Plugins' menu in WordPress.
3. **Configuration**: No further configuration is needed, the plugin works out of the box following activation.

## Usage

Once activated, the plugin automatically begins to monitor and restrict user logins to a single device based on a unique device hash saved in cookies. If a user tries to log in from a second device where the device hash does not match the hash stored in the database, or if no hash is found in the device's cookies, the login will be blocked, and an error message will be displayed. The user must then contact an administrator to reset the device association through the 'Reset Device' button available in the user profile settings.

## Frequently Asked Questions

**Q: What happens if a user tries to log in from a second device?**
A: The user will receive an error message stating "Device Mismatch - You can only log in from your registered device." To resolve this, the user must contact an administrator to reset the device association through the 'Reset Device' button in their user profile settings.

**Q: Is there any user interface to control settings?**
A: Yes, administrators have access to a 'Reset Device' button in user profiles. This button allows them to reset the device hash associated with a user account, enabling login from a new device.

## Changelog

### 1.0.0
- Initial release of the plugin.

## License

This plugin is licensed under the GPL-2.0+ license. For more details, see the `License URI` provided in the plugin header.

