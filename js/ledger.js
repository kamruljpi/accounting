$('#generalLedgerForm').submit(function(ev)
{
	
	ajaxFunction("/searchGeneralLedger",$("#generalLedgerForm").serialize(),'/detailsGeneralJournalOfLedger');
	ev.preventDefault();
});

$('#partyLedgerForm').submit(function(ev)
{
	
	ajaxFunction("/searchPartyLedger",$("#partyLedgerForm").serialize(),'/detailsPartyJournalOfLedger');
	ev.preventDefault();
});

function ajaxFunction(url, data, detailsUrl)
{
	$('.preLoadImageView').css('display','block');
	$.ajax({
		url: base_url+url,
		type:"POST",
		data: data,
		success:function(ledgers)
		{
			
			if (ledgers != -1)
			{
				if(ledgers.length >= 1)
				{
					$('.preLoadImageView').css('display','none');
					addledgersRow(ledgers);
					journalDetailsView(detailsUrl);
				}
				else
					dataNotFoundView();
				$('.preLoadImageView').css('display','none');
			}
			else
				alert('All fileds need to be field');
			$('.preLoadImageView').css('display','none');
		},
		error:function(er) {
			alert("ERROR "+er);
			$('.preLoadImageView').css('display','none');
		}
	});
}

function addledgersRow(ledgers)
{
	$('.dataNotFoundMessage').css('display','none');
	$('.ledgerDataView').css('display','block');
	$('.ledgerDetails').html('<b>Ledger: </b>'+ledgers[0].ledgerName);
	$('.dateFrom').html('<b>Date From: </b>'+ledgers[0].journal_date);
	$('.dateTo').html('<b>Date To: </b>'+ledgers[ledgers.length-1].journal_date);
	$('.gnrlLdgrTableChild').remove();

	addRowInTable('', '', 'Openning Balance', '', '', ledgers[0].openingbalance+ledgers[0].credit-ledgers[0].debit, '');
	
	var i = 1;
	var totalCredit = 0;
	var totalDebit = 0;

	ledgers.forEach(function(ledger) 
	{
		button = '<a class="btn btn-info journalDetailsBtn" data-index='+ledger.journal_posting_id+'>Details</a>';
		
		addRowInTable(i, ledger.journal_date, ledger.particular, ledger.credit, ledger.debit, ledger.openingbalance, button);
		
		i++;
		totalCredit += ledger.credit;
		totalDebit += ledger.debit;
	});

	addRowInTable('', '', 'Total', totalCredit, totalDebit, '', '');
}

function addRowInTable(sl, date, particular, credit, debit, balance, button)
{
	$('#LdgrTableBody').append('<tr class="gnrlLdgrTableChild"><td>'+sl+
		'</td><td>'+date+
		'</td><td>'+particular+
		'</td><td>'+credit+
		'</td><td>'+debit+
		'</td><td>'+balance+
		'</td><td>'+button+'</td></tr>');
}

function dataNotFoundView()
{
	$('.preLoadImageView').css('display','none');
	$('.ledgerDataView').css('display','none');
	$('.dataNotFoundMessage').css('display','block');
	$('.dataNotFoundMessage').html('<div class="alert alert-danger" role="alert"><li><span>No Data found</span></li><li><span>Please refilled the input flied</span></li></div>');
}

function journalDetailsView(detailsUrl)
{
	$('.journalDetailsBtn').click(function(ev) {
		var journal_id = $(this).attr("data-index");
		ajaxFunctionForDetails(detailsUrl, journal_id);
		ev.preventDefault();
	});
}

function ajaxFunctionForDetails(url, data)
{
	$.ajax({
		url: base_url+url,
		type:"GET",
		data: "id="+data,
		success:function(detailsView)
		{
			$('.ledgerDetailsView').html(detailsView);
			$('#myModal').modal("show");
		},
		error:function(er) {
			alert("ERROR "+er);
		}
	});
}
