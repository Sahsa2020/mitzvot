import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { environment } from '../../../environments';

@Component({
  selector: 'app-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class FooterComponent implements OnInit {

  public SERVER_URL: string = environment.serverUrl;
  constructor() { }

  ngOnInit() {
  }

}
