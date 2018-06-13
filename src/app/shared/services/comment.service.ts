import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Status} from "../classes/status";
import {Comment} from "../classes/comment";
import {Observable} from "rxjs/Observable";

@Injectable()
export class CommentService {


//define the API endpoint
	private commentUrl = "api/comment/";

	constructor(protected http: HttpClient) {
	}
//call the Comment API and create a new comment
	createComment(comment: Comment): Observable<Status> {
		return (this.http.post<Status>(this.commentUrl, comment));
	}

// call to the Comment API and get a Comment object by its primary key, id
	getCommentByCommentId(id: string): Observable<Comment> {
		return (this.http.get<Comment>(this.commentUrl + id));
	}

// call to the Comment API and get a Comment object by its foreign key, profile id
	getCommentByCommentProfileId(commentProfileId: string): Observable<Comment[]> {
		return (this.http.get<Comment[]>(this.commentUrl + commentProfileId));

	}

// call to the Comment API and get a Comment object by its foreign key,  trail id
	getCommentByCommentTrailId(commentTrailId: string): Observable<Comment[]> {
		return (this.http.get<Comment[]>(this.commentUrl + "?commentTrailId=" + commentTrailId));
	}

// call to the Comment API and get a Comment content
	getCommentByCommentContent(commentContent: string): Observable<Comment[]> {
		return (this.http.get<Comment[]>(this.commentUrl + commentContent));
	}

// call to the Comment API and get a Comment timestamp
	getCommentByCommentTimestamp(commentTimestamp: string): Observable<Comment[]> {
		return (this.http.get<Comment[]>(this.commentUrl + commentTimestamp));
	}
}