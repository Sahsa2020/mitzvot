import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../../language.service';
import { StateService } from '../../../state.service';
import { GeneralService } from '../../../general.service';
import { AuthenticateService, USER_SIGNED_INFO, USER_TYPE } from '../../../authenticate.service';
import { environment } from '../../../../environments';
declare var jQuery:any;
declare var Simditor: any;
@Component({
  selector: 'app-main-personal-account',
  templateUrl: './main.component.html',
  styleUrls: ['./main.component.css']
})
export class MainComponent implements OnInit {
  public editor: any = null;
  public friends = [];
  public posts: any[] = [];
  public SERVER_URL: string = environment.serverUrl;
  public activeTab: string = 'ALL';
  public USER_SIGNED_INFO: any = USER_SIGNED_INFO;
  public USER_TYPE = USER_TYPE;  
  constructor(public lang: LanguageService, public general: GeneralService, public router: Router, public appState: StateService, public authenticate: AuthenticateService) {
    this.authenticate.validateToken();

    this.appState.setLoading('Loading....');
    this.general.getFriends('').subscribe(result => {
      this.friends = result.data;
      this.appState.closeLoading();
    });
  }
  
  ngOnInit() {
    this.getPosts();
    this.editor = new Simditor({
      textarea: jQuery('.main-editor'),
      toolbar: ['title', 'bold', 'italic', 'underline', 'strikethrough', 'fontScale', 'color', 'ol', 'ul', 'blockquote', 'hr', 'indent', 'outdent', 'alignment', 'image', 'link']
    });
  }

  createPost() {
    let text = this.editor.getValue();
    this.general.createNewPost(text).subscribe(result => {
      this.editor.setValue('');
      this.getPosts();
    });
  }

  getPosts() {
    if(this.activeTab == 'ALL') {
      this.general.getAllPosts("").subscribe(result => {
        this.posts = result;
      });
    }
    if(this.activeTab == 'FRIENDS') {
      this.general.getAllPosts("/getFriendsPosts").subscribe(result => {
        this.posts = result;
      });
    }
    if(this.activeTab == 'MINE') {
      this.general.getAllPosts("/getMyPosts").subscribe(result => {
        this.posts = result;
      });
    }
  }

  commenting(event, post) {
    if(event.keyCode == 13 && !event.shiftKey) {
      let comment: any = jQuery(event.target).val();
      this.general.createNewComment(comment, post.id).subscribe(result => {
        this.getPosts();
      });
    }
  }

  likePost(post) {
    this.general.likePost(post.id).subscribe(result => {
        this.getPosts();
      },
      error => {
        this.appState.errorMessage = 'You have already liked this post.';
      }
    )
  }

  setTab(tab) {
    this.activeTab = tab;
    this.getPosts();
  }
}
