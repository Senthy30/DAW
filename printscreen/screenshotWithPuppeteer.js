const puppeteer = require("puppeteer");
 
(async () => {
  const browser = await puppeteer.launch({
    defaultViewport: {
      width: parseInt(process.argv[4]),
      height: parseInt(process.argv[5]),
    },
  });
 
  const page = await browser.newPage();
  await page.goto(process.argv[2]);
  await page.waitForTimeout(10);
  await page.screenshot({ path: process.argv[3] });
 
  await browser.close();
})();