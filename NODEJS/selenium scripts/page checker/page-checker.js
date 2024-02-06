const { Builder, By, Key, until } = require('selenium-webdriver');
const firefox = require('selenium-webdriver/firefox');

class PageLoadChecker {
  constructor(url, recommendedTime) {
    this.url = url;
    this.recommendedTime = recommendedTime;
    this.driver = new Builder().forBrowser('firefox').build();
    this.waitTime;
    // wait time of 60 seconds
    this.waitTime = 60000;
    
  }

  async measurePageLoadTime() {
    try {
      // Start measuring time
      const startTime = new Date().getTime();

      // Navigate to the URL
      await this.driver.get(this.url);

    //   // Wait for the page to have url 
      await this.driver.wait(until.urlIs(this.url), this.waitTime);

      

      // Calculate load time
      const loadTime = new Date().getTime() - startTime;

      // Check if load time exceeds recommended time
      if (loadTime > this.recommendedTime) {
        console.log(`Page load time exceeded recommended time: ${loadTime}ms`);
        // You can perform additional actions or notify as needed
      } else {
        console.log(`Page loaded within recommended time: ${loadTime}ms`);
      }
    } finally {
      // Close the browser
      await this.driver.quit();
    }
  }
}

// Example usage
const url = 'https://www.leadershipknoxville.com/alumni/';
const recommendedTime = 1000; // 3 seconds

const pageLoadChecker = new PageLoadChecker(url, recommendedTime);
pageLoadChecker.measurePageLoadTime();
