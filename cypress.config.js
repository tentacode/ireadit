const { defineConfig } = require("cypress");

module.exports = defineConfig({
  e2e: {
    setupNodeEvents(on, config) {
      config.viewportWidth = 1040;
      config.video = false;
      config.chromeWebSecurity = false;

      if (!config.baseUrl) {
        config.baseUrl = 'http://ireadit.test/'; 
      }

      return config;
    }
  },
  "env": {
    "MAILDEV_PROTOCOL": "http",
    "MAILDEV_HOST": "127.0.0.1",
    "MAILDEV_SMTP_PORT": "1025",
    "MAILDEV_API_PORT": "1080"
  }
});
