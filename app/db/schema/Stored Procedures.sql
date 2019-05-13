SPU_CREATE_STUDENT(@firstName, @lastName){

SELECT * FROM student WHERE FName = @firstName AND LName = @lastName
IF @@ROWCOUNT > 0
	RETURN(0) --ERROR, student already exists
END

INSERT INTO student (FName, LName) VALUES (@firstName, @lastName)
IF @@ROWCOUNT = 0
	RETURN(2) --ERROR, student could not be added
END

RETURN(1) --Success
}

SPU_CREATE_USER(@schoolID, @firstName, @lastName, @email, @password, @accountType, @recoveryPassword,){

SELECT * FROM useraccount WHERE SchoolID = @schoolID AND FName = @firstName AND LName = @lastName 
AND Email = @email AND Password = @password AND AccountType = @accountType AND RecoveryPassword = @recoveryPassword
IF @@ROWCOUNT > 0
	RETURN(0) --ERROR, user already exists
END

INSERT INTO useraccount (SchoolID, FName, LName, Email, Password, AccountType, RecoveryPassword) 
		VALUES (@schoolID, @firstName, @lastName. @email, @password, @accountType, @recoveryPassword)
IF @@ROWCOUNT = 0
	RETURN(2) --ERROR, user could not be added
END

RETURN(1) --Success
}

SPU_CREATE_TEACHER(@schoolID, @userID){

SELECT * FROM teacher WHERE SchoolID = @schoolID AND UserID = @userID
IF @@ROWCOUNT > 0
	RETURN(0) --ERROR, teacher already exists
END

INSERT INTO teacher (SchoolID, UserID) VALUES (@schoolID, @userID)
IF @@ROWCOUNT = 0
	RETURN(2) --ERROR, teacher could not be added
END

RETURN(1) --Success
}
