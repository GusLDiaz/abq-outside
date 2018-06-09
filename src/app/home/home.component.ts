import {Component} from "@angular/core";
import {TrailService} from "../shared/services/trail.service";

@Component({
	template: require("./home.component.html"),
	selector: "home"
})

export class HomeComponent{

	constructor(protected trailservice : TrailService){
	}
}


