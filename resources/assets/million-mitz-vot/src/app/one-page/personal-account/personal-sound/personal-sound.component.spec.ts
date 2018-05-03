import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PersonalSoundComponent } from './personal-sound.component';

describe('PersonalSoundComponent', () => {
  let component: PersonalSoundComponent;
  let fixture: ComponentFixture<PersonalSoundComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PersonalSoundComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PersonalSoundComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
