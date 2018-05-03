import { Component, OnInit, ViewEncapsulation, ElementRef, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../../language.service';
import { StateService } from '../../../state.service';
import { GeneralService } from '../../../general.service';
import { AuthenticateService, USER_SIGNED_INFO, USER_TYPE } from '../../../authenticate.service';
import { environment } from '../../../../environments';
import { ProfileService } from '../../';
import { Box } from '../../..//model/box.type';

declare var jQuery:any;

@Component({
  selector: 'app-personal-sound',
  templateUrl: './personal-sound.component.html',
  styleUrls: ['./personal-sound.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class PersonalSoundComponent implements OnInit {

  public errorMessage: string = "";
  public successMessage: string = "";
  public sound_id: number = -1;
  public sounds: any[] = [];
  constructor(public authenticate:AuthenticateService, public lang: LanguageService, public router: Router, public appState: StateService, public profileService: ProfileService, public elementRef: ElementRef) {
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.getSounds().subscribe(
     result => {
       if(result === false){
          this.appState.errorMessage = this.tr("GET_FAILED");
       }
       else{
          this.sounds = result;
          this.errorMessage = "";
       }
       this.appState.closeLoading();
     });
  }
  
  ngOnInit() {
  }

  onSubmit(soundForm){
    if(!soundForm.form.valid)
    {
      // this.errorMessage = "Please;
      return;
    }
    var soundFiles = jQuery(this.elementRef.nativeElement).find('.imageFile')[0].files;
    if(soundFiles.length == 0)
    {
      return;
    }
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.saveSoundFiles(soundFiles[0]).subscribe(
     result => {
       if(result != false)
       {
         this.successMessage = "Sound Files have been uploaded successfully.";
         this.errorMessage = "";
         this.sounds.push(result);
       }
       else
       {
         this.appState.errorMessage = "Uploading failed.";
       }
       this.appState.closeLoading();
     });
  }

  removeSound(){
    if(this.sound_id < 0)
      return;
    this.appState.setLoading(this.tr("LOADING_TEXT"));
    this.profileService.removeSoundFile(this.sound_id).subscribe(
     result => {
       if(result != false)
       {
         this.successMessage = "Sound Files have been removed successfully.";
         this.errorMessage = "";
         for(let i = 0; i < this.sounds.length; i++){
           if(this.sounds[i].id == this.sound_id){
             this.sounds.splice(i, 1);
             break;
           }
         }
       }
       else
       {
         this.appState.errorMessage = "Uploading failed.";
       }
       this.appState.closeLoading();
     });
  }

  tr(tran: string): string
  {
    return this.lang.tr("profile." + tran);
  }

}
