import { CboxPage } from './app.po';

describe('cbox App', function() {
  let page: CboxPage;

  beforeEach(() => {
    page = new CboxPage();
  });

  it('should display message saying app works', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('app works!');
  });
});
