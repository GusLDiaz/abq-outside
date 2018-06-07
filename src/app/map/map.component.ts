import {Component, OnInit} from "@angular/core";
import {ActivatedRoute, Params} from "@angular/router";
import {Status} from "../classes/status";
import {Observable} from "rxjs";
import "rxjs/add/observable/from";
import "rxjs/add/operator/switchMap";
import 'rxjs/add/observable/of';
import {TrailService} from "../services/trail.service";
import {Trail} from "../classes/trail";
import {Point} from "../classes/point";


@Component({
	template: require("./map.component.html"),
	selector: "map"
})

export class MapComponent implements OnInit {

	// empty array of lat/long points
	trail: Trail = new Trail(null, null, null, null, null, null, null, null, null, null);
	trails: Trail[] = [];
	data: Observable<Array<Trail[]>>;
	point: any;

	constructor(
		protected trailService : TrailService) {}

	ngOnInit() : void {
		this.listTrails();
	}

	listTrails() : any {
		this.trailService.trailObserver
			.subscribe(trails => this.trails = trails);
	}

	clicked({target: marker} : any, trail : Trail) {
		this.trail = marker;
		marker.nguiMapComponent.openInfoWindow('trail-details', marker);
	}
	hideMarkerInfo() {
		this.point.display = !this.point.display;
	}

	displayTrail(trail: Trail) {
		this.trail = trail;
	}
}