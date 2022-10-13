BEGIN
DECLARE CardExist INTEGER;

IF p_action = 'Insert' THEN
	INSERT INTO card_info (card_type, card_holder, card_number, card_exp_year, card_exp_month, card_csv) VALUES (p_card_type, p_card_holder, p_card_number, p_card_exp_year, p_card_exp_month, p_card_csv);
END

END