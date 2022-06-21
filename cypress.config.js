const { defineConfig } = require("cypress");

module.exports = defineConfig({
  e2e: {
    setupNodeEvents(on, config) {
      config.video = false;

      if (!config.baseUrl) {
        config.baseUrl = 'http://ireadit.test/'; 
      }

      return config;
    }
  },
});
