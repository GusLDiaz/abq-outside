import {Component, OnInit} from "@angular/core"
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {TrailService} from "../shared/services/trail.service";
import {Trail} from "../shared/classes/trail";
import {CommentService} from "../shared/services/comment.service";
import {Comment} from "../shared/classes/comment";
import {ActivatedRoute} from "@angular/router";
import {Status} from "../shared/classes/status";

@Component({
	template: require("./trail.component.html")
})
export class TrailComponent implements OnInit {
	trail: Trail = new Trail(null, null, null, null, null, null, null, null, null, null, null, null);
	trails: Trail[] = [];
	comments: Comment[] = [];
	comment: Comment = new Comment(null, null, null, null, null);
	trailId = this.route.snapshot.params.trailId;
	detailedComment: Comment = new Comment(null, null, null, null, null);
	commentCreator: FormGroup;
	status: Status = new Status(null, null, null);
	constructor(protected trailService: TrailService, protected formBuilder: FormBuilder, protected commentService: CommentService, protected route: ActivatedRoute) {
	}

	ngOnInit() {
		this.trailService.getTrailByTrailId(this.trailId).subscribe(reply => this.trail = reply);
		this.commentService.getCommentByCommentTrailId(this.trailId).subscribe(comments => this.comments = comments);
		//this.showComment(this.comment);
		this.commentCreator = this.formBuilder.group({
			commentContent: ["", [Validators.maxLength(2000), Validators.required]]
		});
	}

	createTrailComment(): any {
		this.comment = new Comment(null, null, this.trailId, this.commentCreator.value.commentContent, null);

		this.commentService.createComment(this.comment)
			.subscribe(status =>
				this.status = status);
		// if(status.status === 200) {
		// 	this.showComment(this.comment);
		// 	this.commentCreator.reset();
		// } else {
		// 	return false
		// }
	}
	showComment(comment: Comment): void {
		this.detailedComment = comment;
	}
}