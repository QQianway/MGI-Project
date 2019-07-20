<script type="text/javascript" src="./dashboard/assets/js/java.js"></script>
<?php 
	require("config.php");
//	$genomeID=2;
	function rrmdir($dir) { 
	   if (is_dir($dir)) { 
	     $objects = scandir($dir); 
	     foreach ($objects as $object) { 
	       if ($object != "." && $object != "..") { 
		 if (is_dir($dir."/".$object))
		   rrmdir($dir."/".$object);
		 else
		   unlink($dir."/".$object); 
	       } 
	     }
	     rmdir($dir); 
	   } 
	 }
	if(isset($_POST['genomeID']))
	{
		$genomeID=$_POST['genomeID'];
		$getScaffoldQuery=mysqli_query($conn,"select * from scaffold where geneID=$genomeID");
		while($getScaffoldQueryRows=mysqli_fetch_array($getScaffoldQuery)){
			$scaffoldID=$getScaffoldQueryRows['scaffoldID'];
			if (file_exists('../data/plotter/'.$scaffoldID.'.png')){
				unlink('../data/plotter/'.$scaffoldID.'.png');
			}
			if (file_exists('../data/scaffold/'.$scaffoldID.'.fasta')){
				unlink('../data/scaffold/'.$scaffoldID.'.fasta');
			}
			$trnaseq=mysqli_query($conn,"delete from trnaseq where tags like '%$scaffoldID%'");
			$trna=mysqli_query($conn,"delete from trna where scaffoldID like '%$scaffoldID%'");
			$rrna=mysqli_query($conn,"select *  from rrna where scaffoldID like '%$scaffoldID%'");
			$rrnarow=mysqli_fetch_array($rrna);
			if (strpos($rrnarow['source'],'RNAmmer') !==false){
				$rrnaseq=mysqli_query($conn,"delete from rrnaseq where tags like '%$scaffoldID%'");
			}
			else if (strpos($rrnarow['source'],'FIG') !==false){
				$attribute=$rrnarow['attribute'];
				$part=explode('=',$attribute);
				$temp=$part[1];
				$part=explode('.',$part[1]);
				$id=$part[0].".".$part[1];  
				$rrnaseq=mysqli_query($conn,"delete from rrnaseq where tags like '%$id%'");
			} 
			$rrna=mysqli_query($conn,"delete from rrna where scaffoldID like '%$scaffoldID%'");
			$mapping=mysqli_query($conn,"delete from mapping where seqName like '%$scaffoldID%'");
			$geneseq=mysqli_query($conn,"delete from geneseq where geneSeqName like '%$scaffoldID%'");
			$genes=mysqli_query($conn,"delete from genes where scaffoldID like '%$scaffoldID%'");
			$blastresult=mysqli_query($conn,"delete from blastresult where seqName like '%$scaffoldID%'")or die (mysqli_error($conn));
			$scaffold=mysqli_query($conn,"delete from scaffold where scaffoldID='$scaffoldID'");
		}	
		$getGenomeName=mysqli_query($conn,"select genomeName from genome where genomeID=$genomeID");
		$getGenomeNameRow=mysqli_fetch_array($getGenomeName);
		$genomeName=$getGenomeNameRow['genomeName'];
		if (file_exists('../data/blast2go/'.$genomeName.'.blast2go.csv')){
				unlink('../data/blast2go/'.$genomeName.'.blast2go.csv');
		}
		if (file_exists('../data/fasta/'.$genomeName.'.fasta')){
			unlink('../data/fasta/'.$genomeName.'.fasta');
		}
		if (file_exists('../data/genedetail/'.$genomeName.'.predictedgene.gff')){
			unlink('../data/genedetail/'.$genomeName.'.predictedgene.gff');
		}
		if (file_exists('../data/genesequenceAA/'.$genomeName.'.predictedgene.faa')){
			unlink('../data/genesequenceAA/'.$genomeName.'.predictedgene.faa');
		}
		if (file_exists('../data/genesequenceN/'.$genomeName.'.predictedgene.ffn')){
			unlink('../data/genesequenceN/'.$genomeName.'.predictedgene.ffn');
		}
		if (file_exists('../data/mapping/'.$genomeName.'.mapping.csv')){
			unlink('../data/mapping/'.$genomeName.'.mapping.csv');
		}
		if (file_exists('../data/rrnadetail/'.$genomeName.'.rrna.gff')){
			unlink('../data/rrnadetail/'.$genomeName.'.rrna.gff');
		}
		if (file_exists('../data/rrnsequence/'.$genomeName.'.rrna.fasta')){
			unlink('../data/rrnaseqeunce/'.$genomeName.'.rrna.fasta');
		}
		if (file_exists('../data/trnadetail/'.$genomeName.'.trna.gff')){
			unlink('../data/trnadetail/'.$genomeName.'.trna.gff');
		}
		if (file_exists('../data/trnsequence/'.$genomeName.'.trna.fasta')){
			unlink('../data/trnaseqeunce/'.$genomeName.'.trna.fasta');
		}
		if (file_exists('../blast/db/'.$genomeName.'FAA.phr')){
			unlink('../blast/db/'.$genomeName.'FAA.phr');
		}
		if (file_exists('../blast/db/'.$genomeName.'FAA.pin')){
			unlink('../blast/db/'.$genomeName.'FAA.pin');
		}
		if (file_exists('../blast/db/'.$genomeName.'FAA.psq')){
			unlink('../blast/db/'.$genomeName.'FAA.psq');
		}
		if (file_exists('../blast/db/'.$genomeName.'FFN.nhr')){
			unlink('../blast/db/'.$genomeName.'FFN.nhr');
		}
		if (file_exists('../blast/db/'.$genomeName.'FFN.nin')){
			unlink('../blast/db/'.$genomeName.'FFN.nin');
		}
		if (file_exists('../blast/db/'.$genomeName.'FFN.nsq')){
			unlink('../blast/db/'.$genomeName.'FFN.nsq');
		}
		if (file_exists('../blast/db/'.$genomeName.'FASTA.nhr')){
			unlink('../blast/db/'.$genomeName.'FASTA.nhr');
		}
		if (file_exists('../blast/db/'.$genomeName.'FASTA.nin')){
			unlink('../blast/db/'.$genomeName.'FASTA.nin');
		}
		if (file_exists('../blast/db/'.$genomeName.'FASTA.nsq')){
			unlink('../blast/db/'.$genomeName.'FASTA.nsq');
		}
		if (file_exists('../jbrowse/data/'.$genomeName.'/')){
			$filedir = '../jbrowse/data/'.$genomeName.'/';
			rrmdir($filedir);
		}
		$getGenomeCount=mysqli_query($conn,"select count(*) from genome");
		$getGenomeCountRows=mysqli_fetch_array($getGenomeCount);
		$genomeCount=$getGenomeCountRows['count(*)'];
		if ($genomeCount>1){
			$getAllGenomeName=mysqli_query($conn,"select* from genome");
			$allDB="";
			while($getAllGenomeNameRows=mysqli_fetch_array($getAllGenomeName)){
				$allgenomeName=$getAllGenomeNameRows['genomeName'];
				$allDBFFN=$allDB."../db/".$allgenomeName."FFN ";
				$allDBFAA=$allDB."../db/".$allgenomeName."FAA ";
				$allDBFASTA=$allDB."../db/".$allgenomeName."FASTA ";
			} 
			$dbtypenucl="-dbtype nucl ";
			$dbtypeprot="-dbtype prot ";
			$mergeDB="blastdb_aliastool ";
			$listDBFFN="-dblist \"".$allDBFFN."\" ";
			$listDBFAA="-dblist \"".$allDBFAA."\" ";
			$listDBFASTA="-dblist \"".$allDBFASTA."\" ";
			$outMergeDBFFN="-out ../db/FFN ";
			$outMergeDBFAA="-out ../db/FAA ";
			$outMergeDBFASTA="-out ../db/FASTA ";
			$titleNUCL="-title \"Nuclueotide Seqeunces Database\"";
			$titlePROT="-title \"Whole Genome Database\"";
			$titleFASTA="-title \"Amino Acid Sequences Database\"";
			$mergeCommandNUCL=$mergeDB.$listDBFFN.$outMergeDBFFN.$dbtypenucl.$titleNUCL;
			$mergeCommandPROT=$mergeDB.$listDBFAA.$outMergeDBFAA.$dbtypeprot.$titlePROT;
			$mergeCommandFASTA=$mergeDB.$listDBFASTA.$outMergeDBFASTA.$dbtypenucl.$titleFASTA;
			chdir("../blast/bin/");
			exec($mergeCommandNUCL);
			exec($mergeCommandPROT);
			exec($mergeCommandFASTA);
			//echo ('../blast/db/'.$genomeName.'FNN.nsq');
		}
		$genome=mysqli_query($conn,"delete from genome where genomeID=$genomeID")or die(mysqli_error($conn));
		?><script>deleteSuccess();</script><?php 
	}
?>
