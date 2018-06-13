import {Component, OnInit} from "@angular/core"
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {TrailService} from "../shared/services/trail.service";
import {Trail} from "../shared/classes/trail";
import {CommentService} from "../shared/services/comment.service";
import {Comment} from "../shared/classes/comment";
import {ActivatedRoute} from "@angular/router";
import {Status} from "../shared/classes/status";
import {SessionService} from "../shared/services/session.service";
import {CookieService} from "ng2-cookies";

@Component({
	template: require("./trail.component.html")
})
export class TrailComponent implements OnInit {
	trail: Trail = new Trail(null, null, null, null, null, null, null, null, null, null, null, null);
	trails: Trail[] = [];
	comments: Comment[] = [];
	comment: Comment = new Comment(null, null, null, null, null);
	trailId = this.route.snapshot.params.trailId;
	//detailedComment: Comment = new Comment(null, null, null, null, null);
	commentCreator: FormGroup;
	status: Status = new Status(null, null, null);

	constructor(protected trailService: TrailService, protected formBuilder: FormBuilder, protected commentService: CommentService, protected route: ActivatedRoute, protected sessionService: SessionService) {
	}


	ngOnInit() {
		this.sessionService.setSession();
		this.trailService.getTrailByTrailId(this.trailId).subscribe(reply => this.trail = reply);
		this.commentService.getCommentByCommentTrailId(this.trailId).subscribe(comments => this.comments = comments);
		//this.showComment(this.comment);
		this.commentCreator = this.formBuilder.group({
			commentContent: ["", [Validators.maxLength(2000), Validators.required]]
		});
	}

	createTrailComment(): any {
		let status = new Status("200", null, null);
		this.comment = new Comment(null, null, this.trailId, this.commentCreator.value.commentContent, null);
//this.comment['commentProfileId'] = '4fa9ccdf-6f6c-489d-bcec-aa0b5d92faf2'
		//getProfileComments();
		this.commentService.createComment(this.comment)
			.subscribe(Status => this.status = Status);
		if(this.status.status == status.status) {
			this.commentCreator.reset();
		} else {
			return false
		}
	}

	//showComment(comment: Comment): void {
	//this.detailedComment = comment;
	//}

	getProfileComments(): void {
		this.sessionService.setSession();

		//this.sessionService.
		//this.cookieJar = this.cookieService.getAll();
		//this.cookieJar['profileId'].subscribe(profiles => this.profiles = profiles);
	}
	usermap : object = {
		"61fadb93-8d1c-4124-a505-7ae0b69662e0": "HappyHikerMan",
		"7d6020d2-7763-472e-bfa7-e36ac013f02e": "MrTrailGuy",
		"f4ac03a6-637d-4221-b9e2-76a1670983eb": "JullyDaHikeQueen"
	}
}