import { Builder, Browser, By, Key, until } from 'selenium-webdriver';
// dotconfig
 import dotenv from 'dotenv';
import current_class_ids from './data/leadership-class-member-ids.js';
    dotenv.config();
    // username and password from .env
   
let index = 0; 


class EditPage {
    constructor(cb, id) {
    this.cb = cb;
    this.username = process.env.USERNAME;
    this.password = '#' + process.env.PASSWORD;
    this.id = id || 2210;
    this.driver = null;
    
    this.waittime = 1000 * 60 * 5;
   

    console.log({
        username: this.username,
        password: this.password
    });

    }
  async run() {
    // Set up capabilities (you can customize this based on your needs)
    let capabilities = {
      browserName: 'firefox',
      // bypass security so we can run on http://localhost
        acceptInsecureCerts: true,
        acceptSslCerts: true,

        // acceptInsecureCerts: true,
    };

    this.driver = await new Builder().withCapabilities(capabilities).build();

    try {
    this.cb && await this.cb();
    } finally {
      await this.driver.quit();
    }
  }
}

// // Instantiate the class and run the example
// const editPage = new EditPage(driver_log);
// editPage.run();
// console.log('current_class_ids', current_class_ids);

current_class_ids.slice(0,1).forEach((id) => {
    
    const editPage = new EditPage(driver_log, id);
    editPage.run();
    index++;
}
);



async function driver_log() {
   let base_url = 'https://www.leadershipknoxville.com'
   let edit_url = `${base_url}/wp-admin/post.php?post=${this.id}&action=edit`;
   let title = "Home - Leadership Knoxville"
  
    await this.driver.get( edit_url );
    await this.driver.sleep(1000);
    await this.driver.findElement(By.id('user_login')).sendKeys(this.username);
    await this.driver.findElement(By.id('user_pass')).sendKeys(this.password);
    await this.driver.findElement(By.id('wp-submit')).click();

  //  wait unitl title is loaded
  await this.driver.wait(until.titleIs(title), this.waittime);

  // next page 
 let next_id =  current_class_ids[index];
 const editPage = new EditPage(driver_log, next_id);
  editPage.run();
  index++;
  

   
}   

