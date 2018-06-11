import {Component, OnInit} from "@angular/core";
import {TrailService} from "../shared/services/trail.service";
import {Trail} from "../shared/classes/trail";
import {Router} from "@angular/router";
@Component({
	template: require("./home.component.html"),
	selector: "home"
})
export class HomeComponent implements OnInit{
	trail : string = "";
	trails : Trail[] = [];
	detailedTrail : Trail = new Trail(null,null,null,null,null,null,null,null,null,null,null,null);
	constructor(protected trailService : TrailService, protected router: Router){ //protected v private v public?

	}
	ngOnInit() : void {
		this.trailService.getAllTrails()
			.subscribe(trails => this.trails = trails);
	}
	showSidePanel( trail : Trail) {
	this.detailedTrail = trail;
	}
	goToTrail(){
		this.router.navigate(['trail/' + this.detailedTrail.trailId]);
	}
}



